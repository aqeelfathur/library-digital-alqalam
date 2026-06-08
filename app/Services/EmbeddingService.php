<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

/**
 * EmbeddingService — Mengelola vector store di tabel document_embeddings.
 *
 * Tanggung jawab:
 *   1. embedAllDocuments()  → embed seluruh buku + info perpustakaan (saat setup awal)
 *   2. embedBiblio()        → embed satu atau semua buku
 *   3. embedLibraryInfo()   → embed satu atau semua dokumen info
 *   4. findSimilar()        → cari dokumen paling relevan via cosine similarity
 *   5. deleteEmbedding()    → hapus embedding jika dokumen sumber dihapus
 */
class EmbeddingService
{
    public function __construct(
        private GeminiService $gemini
    ) {}

    // =========================================================================
    // EMBED DOKUMEN → VECTOR STORE
    // =========================================================================

    /**
     * Embed seluruh dokumen (biblio + library_info) dari awal.
     * Dipanggil oleh Artisan command: php artisan chatbot:embed
     *
     * @param  callable|null  $progress  Callback progress (untuk CLI output)
     * @return array ['biblio' => int, 'library_info' => int, 'errors' => int]
     */
    public function embedAllDocuments(?callable $progress = null): array
    {
        $stats = ['biblio' => 0, 'library_info' => 0, 'errors' => 0];

        // Kosongkan vector store lama
        DB::table('document_embeddings')->truncate();

        if ($progress) {
            $progress('🗑️  Vector store lama dihapus. Mulai embedding ulang...');
        }

        // --- Embed semua buku ---
        $books = DB::table('biblio')
            ->where('available', 1)
            ->whereNotNull('summary_text')
            ->get();

        if ($progress) {
            $progress("📚 Memproses {$books->count()} buku...");
        }

        foreach ($books as $book) {
            try {
                $this->embedSingleBiblio($book);
                $stats['biblio']++;

                if ($progress) {
                    $progress("  ✅ [{$stats['biblio']}/{$books->count()}] {$book->title}");
                }

                // Jeda kecil untuk menghindari rate limit Gemini free tier
                usleep(200000); // 0.2 detik
            } catch (Exception $e) {
                $stats['errors']++;
                Log::error('[EmbeddingService] Gagal embed biblio', [
                    'biblio_id' => $book->biblio_id,
                    'error'     => $e->getMessage(),
                ]);
                if ($progress) {
                    $progress("  ❌ Gagal: {$book->title} — {$e->getMessage()}");
                }
            }
        }

        // --- Embed semua library_info ---
        $infos = DB::table('library_info')
            ->where('is_active', true)
            ->get();

        if ($progress) {
            $progress("\n📋 Memproses {$infos->count()} dokumen informasi perpustakaan...");
        }

        foreach ($infos as $info) {
            try {
                $this->embedSingleLibraryInfo($info);
                $stats['library_info']++;

                if ($progress) {
                    $progress("  ✅ [{$stats['library_info']}/{$infos->count()}] {$info->title}");
                }

                usleep(200000);
            } catch (Exception $e) {
                $stats['errors']++;
                Log::error('[EmbeddingService] Gagal embed library_info', [
                    'id'    => $info->id,
                    'error' => $e->getMessage(),
                ]);
                if ($progress) {
                    $progress("  ❌ Gagal: {$info->title} — {$e->getMessage()}");
                }
            }
        }

        return $stats;
    }

    // =========================================================================
    // EMBED INDIVIDUAL
    // =========================================================================

    /**
     * Embed satu buku (insert/update di document_embeddings).
     * Dipanggil saat buku baru ditambahkan atau diupdate.
     *
     * @param  int  $biblioId
     */
    public function embedBiblio(int $biblioId): void
    {
        $book = DB::table('biblio')->where('biblio_id', $biblioId)->first();

        if (!$book) {
            throw new Exception("Buku dengan biblio_id={$biblioId} tidak ditemukan.");
        }

        // Hapus embedding lama jika ada
        DB::table('document_embeddings')
            ->where('source_type', 'biblio')
            ->where('source_id', $biblioId)
            ->delete();

        $this->embedSingleBiblio($book);
    }

    /**
     * Embed satu dokumen library_info.
     *
     * @param  int  $infoId
     */
    public function embedLibraryInfo(int $infoId): void
    {
        $info = DB::table('library_info')->where('id', $infoId)->first();

        if (!$info) {
            throw new Exception("library_info dengan id={$infoId} tidak ditemukan.");
        }

        DB::table('document_embeddings')
            ->where('source_type', 'library_info')
            ->where('source_id', $infoId)
            ->delete();

        $this->embedSingleLibraryInfo($info);
    }

    // =========================================================================
    // RETRIEVAL — Cosine Similarity
    // =========================================================================

    /**
     * Cari dokumen paling relevan dengan query user.
     *
     * Proses:
     *   1. Embed query user → vektor
     *   2. Hitung cosine similarity dengan semua dokumen di vector store
     *   3. Return top-K dokumen di atas threshold
     *
     * @param  string  $query      Pertanyaan/pesan dari user
     * @param  int     $topK       Jumlah dokumen yang dikembalikan
     * @param  float   $threshold  Minimum similarity score
     * @return array   [['source_type', 'source_id', 'chunk_text', 'score'], ...]
     */
    public function findSimilar(
        string $query,
        int    $topK      = null,
        float  $threshold = null
    ): array {
        $topK      = $topK      ?? config('gemini.rag_top_k', 5);
        $threshold = $threshold ?? config('gemini.similarity_threshold', 0.65);

        // 1. Embed query user
        $queryVector = $this->gemini->embedText($query, 'RETRIEVAL_QUERY');

        // 2. Ambil semua embedding dari DB
        $embeddings = DB::table('document_embeddings')
            ->select('id', 'source_type', 'source_id', 'chunk_text', 'embedding')
            ->get();

        if ($embeddings->isEmpty()) {
            Log::warning('[EmbeddingService] Vector store kosong! Jalankan php artisan chatbot:embed');
            return [];
        }

        // 3. Hitung cosine similarity untuk setiap dokumen
        $scored = [];
        foreach ($embeddings as $doc) {
            $docVector = json_decode($doc->embedding, true);

            if (empty($docVector)) {
                continue;
            }

            $score = $this->cosineSimilarity($queryVector, $docVector);

            if ($score >= $threshold) {
                $scored[] = [
                    'source_type' => $doc->source_type,
                    'source_id'   => $doc->source_id,
                    'chunk_text'  => $doc->chunk_text,
                    'score'       => $score,
                ];
            }
        }

        // 4. Urutkan descending by score, ambil top-K
        usort($scored, fn($a, $b) => $b['score'] <=> $a['score']);

        return array_slice($scored, 0, $topK);
    }

    // =========================================================================
    // DELETE
    // =========================================================================

    /**
     * Hapus embedding saat dokumen sumber dihapus.
     */
    public function deleteEmbedding(string $sourceType, int $sourceId): void
    {
        DB::table('document_embeddings')
            ->where('source_type', $sourceType)
            ->where('source_id', $sourceId)
            ->delete();
    }

    // =========================================================================
    // PRIVATE HELPERS
    // =========================================================================

    /**
     * Buat teks embedding untuk satu buku dari kolom-kolomnya.
     */
    private function buildBiblioChunkText(object $book): string
    {
        // Jika summary_text sudah ada, pakai itu
        if (!empty($book->summary_text)) {
            return $book->summary_text;
        }

        // Fallback: bangun dari kolom individual
        return implode('. ', array_filter([
            'Judul: '     . $book->title,
            'Pengarang: ' . ($book->author_main ?? ''),
            'Penerbit: '  . ($book->publisher_id ?? ''),
            'Tahun: '     . ($book->publish_year ?? ''),
            'Subjek: '    . ($book->subject ?? ''),
            'Sinopsis: '  . ($book->notes ?? ''),
        ]));
    }

    /**
     * Embed satu row biblio → insert ke document_embeddings.
     */
    private function embedSingleBiblio(object $book): void
    {
        $chunkText = $this->buildBiblioChunkText($book);
        $vector    = $this->gemini->embedText($chunkText, 'RETRIEVAL_DOCUMENT');

        DB::table('document_embeddings')->insert([
            'source_type' => 'biblio',
            'source_id'   => $book->biblio_id,
            'chunk_text'  => $chunkText,
            'embedding'   => json_encode($vector),
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);
    }

    /**
     * Embed satu row library_info → insert ke document_embeddings.
     */
    private function embedSingleLibraryInfo(object $info): void
    {
        // Gabungkan title + content sebagai satu chunk
        $chunkText = "{$info->title}. {$info->content}";
        $vector    = $this->gemini->embedText($chunkText, 'RETRIEVAL_DOCUMENT');

        DB::table('document_embeddings')->insert([
            'source_type' => 'library_info',
            'source_id'   => $info->id,
            'chunk_text'  => $chunkText,
            'embedding'   => json_encode($vector),
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);
    }

    /**
     * Hitung cosine similarity antara dua vektor float[].
     *
     * Rumus: cos(θ) = (A·B) / (|A| × |B|)
     * Nilai: -1 (berlawanan) hingga 1 (identik)
     * Untuk teks: biasanya 0.0 – 1.0
     */
    private function cosineSimilarity(array $a, array $b): float
    {
        if (count($a) !== count($b)) {
            return 0.0;
        }

        $dot    = 0.0;
        $normA  = 0.0;
        $normB  = 0.0;

        for ($i = 0; $i < count($a); $i++) {
            $dot   += $a[$i] * $b[$i];
            $normA += $a[$i] ** 2;
            $normB += $b[$i] ** 2;
        }

        $denominator = sqrt($normA) * sqrt($normB);

        if ($denominator == 0.0) {
            return 0.0;
        }

        return $dot / $denominator;
    }
}