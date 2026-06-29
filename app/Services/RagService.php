<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * RagService — Retrieval-Augmented Generation untuk chatbot perpustakaan.
 *
 * Alur kerja saat user mengirim pesan:
 *   1. Cari dokumen relevan di vector store (via EmbeddingService::findSimilar)
 *   2. Enrich konteks: ambil data stok buku real-time dari tabel items
 *   3. Bangun system prompt yang menyertakan konteks tersebut
 *   4. Kirim ke Gemini → dapatkan jawaban
 *   5. Return jawaban + metadata (dokumen yang dipakai, skor relevansi)
 */
class RagService
{
    public function __construct(
        private GeminiService   $gemini,
        private EmbeddingService $embedding,
    ) {}

    // =========================================================================
    // PUBLIC: Entry point utama
    // =========================================================================

    /**
     * Proses pesan user dan hasilkan jawaban chatbot.
     *
     * @param  string  $userMessage   Pesan dari user
     * @param  array   $history       Riwayat chat [['role'=>'user'|'model','text'=>'...']]
     * @return array   [
     *   'answer'    => string,   // jawaban chatbot
     *   'sources'   => array,    // dokumen yang dipakai sebagai konteks
     *   'has_context' => bool,   // apakah ada konteks relevan ditemukan
     * ]
     */
    public function chat(string $userMessage, array $history = []): array
    {
        // 1. Retrieve dokumen relevan dari vector store
        $relevantDocs = $this->embedding->findSimilar(
            query:     $userMessage,
            topK:      config('gemini.rag_top_k', 5),
            threshold: config('gemini.similarity_threshold', 0.65),
        );

        // 2. Enrich dengan data stok real-time jika ada dokumen biblio
        $enrichedDocs = $this->enrichWithStockData($relevantDocs);

        // 3. Bangun system prompt
        $systemPrompt = $this->buildSystemPrompt($enrichedDocs);

        // 4. Generate jawaban dari Gemini
        $answer = $this->gemini->generateText($systemPrompt, $history, $userMessage);

        // 5. Log untuk debugging
        Log::info('[RagService] chat', [
            'user_message'  => mb_substr($userMessage, 0, 100),
            'docs_found'    => count($enrichedDocs),
            'doc_scores'    => array_column($enrichedDocs, 'score'),
        ]);

        return [
            'answer'      => $answer,
            'sources'     => $enrichedDocs,
            'has_context' => count($enrichedDocs) > 0,
        ];
    }

    // =========================================================================
    // PRIVATE: Enrich dengan data stok real-time
    // =========================================================================

    /**
     * Untuk setiap dokumen biblio yang ditemukan, ambil status stok terkini
     * langsung dari tabel items (bukan dari embedding yang mungkin sudah stale).
     *
     * Ini penting: status ketersediaan buku bisa berubah setiap hari,
     * tapi kita tidak mau re-embed setiap ada perubahan stok.
     * Solusi: embedding hanya untuk konten buku, stok diambil real-time.
     */
    private function enrichWithStockData(array $docs): array
    {
        foreach ($docs as &$doc) {
            if ($doc['source_type'] !== 'biblio') {
                continue;
            }

            $biblioId = (int) $doc['source_id'];

            // Ketersediaan LIVE dari SLiMS:
            //   tersedia = item_status_id '0'/''/NULL  DAN  tidak ada loan(is_return=0)
            $stat = DB::connection('slims')->selectOne("
                SELECT
                  COUNT(i.item_id) AS total,
                  SUM(CASE WHEN (i.item_status_id = '0' OR i.item_status_id = '' OR i.item_status_id IS NULL)
                            AND NOT EXISTS (
                                SELECT 1 FROM loan l
                                WHERE l.item_code = i.item_code AND l.is_return = 0
                            )
                           THEN 1 ELSE 0 END) AS tersedia,
                  (SELECT MIN(l.due_date)
                     FROM item i2 JOIN loan l ON l.item_code = i2.item_code AND l.is_return = 0
                    WHERE i2.biblio_id = ?) AS due_terdekat
                FROM item i
                WHERE i.biblio_id = ?
            ", [$biblioId, $biblioId]);

            $total    = (int) ($stat->total ?? 0);
            $tersedia = (int) ($stat->tersedia ?? 0);
            $due      = $stat->due_terdekat ?? null;

            if ($total === 0) {
                $stok = 'Status ketersediaan: tidak ada data eksemplar untuk judul ini.';
            } elseif ($tersedia > 0) {
                $stok = "Status ketersediaan: TERSEDIA ({$tersedia} dari {$total} eksemplar dapat dipinjam).";
            } else {
                $stok = "Status ketersediaan: SEDANG DIPINJAM SEMUA (0 dari {$total} tersedia)"
                      . ($due ? ". Perkiraan eksemplar terdekat kembali: {$due}." : '.');
            }

            $doc['chunk_text'] .= "\n\n[Stok real-time] {$stok}";
        }
        unset($doc);

        return $docs;
    }

    /**
     * Format info stok buku menjadi teks yang mudah dipahami LLM.
     */
    private function formatStockInfo(
        int     $total,
        int     $available,
        int     $borrowed,
        int     $damaged,
        ?string $nearestDueDate
    ): string {
        $status = match(true) {
            $available > 0  => "TERSEDIA ({$available} dari {$total} eksemplar bisa dipinjam)",
            $borrowed > 0   => "SEDANG DIPINJAM SEMUA ({$borrowed} eksemplar dipinjam)" .
                               ($nearestDueDate ? ", paling cepat kembali: {$nearestDueDate}" : ''),
            $damaged > 0    => "TIDAK TERSEDIA (semua eksemplar dalam kondisi rusak/hilang)",
            default         => "STATUS TIDAK DIKETAHUI",
        };

        return "Status ketersediaan: {$status}.";
    }

    // =========================================================================
    // PRIVATE: Bangun System Prompt
    // =========================================================================

    /**
     * Bangun system prompt yang menyertakan konteks dokumen relevan.
     *
     * Struktur prompt:
     *   [Identitas & Peran Chatbot]
     *   [Aturan Menjawab]
     *   [Konteks Dokumen dari RAG]    ← ini yang mencegah hallucination
     *   [Format Jawaban]
     */
    private function buildSystemPrompt(array $docs): string
    {
        $identity = $this->buildIdentitySection();
        $rules    = $this->buildRulesSection();
        $context  = $this->buildContextSection($docs);
        $format   = $this->buildFormatSection();

        return implode("\n\n", array_filter([
            $identity,
            $rules,
            $context,
            $format,
        ]));
    }

    private function buildIdentitySection(): string
    {
        return <<<PROMPT
        Kamu adalah SIPUS (Sistem Informasi Perpustakaan SMAMDA), asisten chatbot resmi Perpustakaan SMA Muhammadiyah 2 Surabaya (SMAMDA).

        Kepribadian kamu:
        - Ramah, sopan, dan membantu seperti petugas perpustakaan yang berpengalaman
        - Menggunakan Bahasa Indonesia yang baik dan mudah dipahami siswa SMA
        - Antusias dalam membantu siswa menemukan buku yang tepat
        - Jujur ketika informasi tidak tersedia daripada mengarang jawaban
        PROMPT;
    }

    private function buildRulesSection(): string
    {
        return <<<PROMPT
        ATURAN MENJAWAB (wajib diikuti):
        1. Jawab HANYA berdasarkan informasi yang ada di bagian KONTEKS di bawah ini.
        2. Jika buku yang ditanyakan tidak ditemukan dalam KONTEKS, artinya buku tersebut
        TIDAK ADA dalam koleksi perpustakaan. Sampaikan dengan jelas bahwa perpustakaan
        tidak memiliki buku tersebut, bukan bahwa kamu tidak punya informasinya.
        Contoh jawaban yang benar: "Buku karya Tere Liye belum tersedia di koleksi
        Perpustakaan SMAMDA saat ini."
        Contoh jawaban yang SALAH: "SIPUS tidak memiliki informasi mengenai buku tersebut."
        3. JANGAN mengarang informasi apapun yang tidak ada di konteks (no hallucination).
        4. Untuk pertanyaan ketersediaan buku, selalu sebutkan status "TERSEDIA" atau
        "SEDANG DIPINJAM" secara eksplisit.
        5. Jika buku sedang dipinjam semua, informasikan tanggal perkiraan kembali jika tersedia.
        6. Tetap fokus pada topik perpustakaan. Tolak pertanyaan di luar topik dengan sopan.
        PROMPT;
    }

    private function buildContextSection(array $docs): string
    {
        if (empty($docs)) {
            return <<<PROMPT
            KONTEKS:
            [Tidak ada informasi relevan ditemukan untuk pertanyaan ini. Sarankan user untuk menghubungi petugas perpustakaan secara langsung.]
            PROMPT;
        }

        $contextParts = [];
        foreach ($docs as $i => $doc) {
            $no      = $i + 1;
            $type    = $doc['source_type'] === 'biblio' ? 'Data Buku' : 'Info Perpustakaan';
            $score   = round($doc['score'] * 100, 1);

            $contextParts[] = <<<PART
            [{$no}] {$type} (relevansi: {$score}%):
            {$doc['chunk_text']}
            PART;
        }

        $contextText = implode("\n\n", $contextParts);

        return <<<PROMPT
        KONTEKS (gunakan HANYA informasi di bawah ini untuk menjawab):
        {$contextText}
        PROMPT;
    }

    private function buildFormatSection(): string
    {
        return <<<PROMPT
        FORMAT JAWABAN:
        - Gunakan paragraf pendek yang mudah dibaca
        - Boleh gunakan poin/list jika ada beberapa item
        - Maksimal 3–4 paragraf kecuali pertanyaannya kompleks
        - Akhiri dengan tawaran bantuan lebih lanjut jika relevan
        PROMPT;
    }
}