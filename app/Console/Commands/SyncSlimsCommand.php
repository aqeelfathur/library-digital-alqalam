<?php

namespace App\Console\Commands;

use App\Services\GeminiService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * slims:sync
 * -----------------------------------------------------------------------------
 * Menarik konten bibliografi dari database SLiMS ASLI (koneksi "slims"),
 * merakitnya menjadi teks yang siap di-embed, lalu menyimpan vektornya ke
 * tabel document_embeddings (vector store yang sudah dipakai RagService).
 *
 * Prinsip:
 *   - KONTEN (judul, pengarang, subjek, sinopsis) -> di-embed sekali di sini.
 *   - STOK/KETERSEDIAAN -> TIDAK di-embed; dibaca real-time oleh RagService
 *     langsung dari SLiMS (item + loan). Lihat RagService::enrichWithStockData().
 *
 * Catatan integrasi (ditemukan dari dump asli SMAMDA):
 *   - Pengarang & subjek ternormalisasi M:N -> dirakit via GROUP_CONCAT.
 *   - publisher/place = FK integer -> di-LEFT JOIN ke mst_*.
 *   - biblio_author bisa yatim (MyISAM tanpa FK) -> LEFT JOIN aman (nama NULL diabaikan).
 *   - publish_year varchar kotor ("384 hlm ; 20 cm", "-", ISBN nyasar) -> disanitasi.
 *   - 17% biblio tanpa notes -> teks embed punya fallback (judul+pengarang+subjek).
 *   - opac_hide=1 dikecualikan dari chatbot.
 */
class SyncSlimsCommand extends Command
{
    protected $signature = 'slims:sync
                            {--fresh : Kosongkan vector store dulu sebelum sync}
                            {--limit= : Batasi jumlah biblio (untuk uji coba, mis. --limit=100)}';

    protected $description = 'Sync konten biblio dari database SLiMS asli ke vector store (document_embeddings) lalu embed via Gemini.';

    public function __construct(private GeminiService $gemini)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        // 0. Pastikan koneksi SLiMS hidup
        try {
            DB::connection('slims')->getPdo();
        } catch (Throwable $e) {
            $this->error('Gagal konek ke database SLiMS (koneksi "slims").');
            $this->line('Cek .env SLIMS_DB_* dan pastikan dump sudah di-import. Detail: ' . $e->getMessage());
            return self::FAILURE;
        }

        if ($this->option('fresh')) {
            DB::table('document_embeddings')->truncate();
            $this->warn('Vector store dikosongkan (--fresh).');
        }

        $limit    = $this->option('limit') ? (int) $this->option('limit') : null;
        $limitSql = $limit ? "LIMIT {$limit}" : '';

        // 1. Tarik biblio dari SLiMS — pengarang & subjek dirakit, orphan-safe
        $books = DB::connection('slims')->select("
            SELECT b.biblio_id, b.title, b.publish_year, b.isbn_issn, b.notes,
                   COALESCE(p.publisher_name, '') AS publisher,
                   COALESCE(pl.place_name, '')    AS place,
                   COALESCE(GROUP_CONCAT(DISTINCT a.author_name ORDER BY ba.level SEPARATOR ', '), '') AS authors,
                   COALESCE(GROUP_CONCAT(DISTINCT t.topic       ORDER BY bt.level SEPARATOR ', '), '') AS topics
            FROM biblio b
            LEFT JOIN mst_publisher p  ON p.publisher_id = b.publisher_id
            LEFT JOIN mst_place pl      ON pl.place_id    = b.publish_place_id
            LEFT JOIN biblio_author ba  ON ba.biblio_id   = b.biblio_id
            LEFT JOIN mst_author a       ON a.author_id    = ba.author_id
            LEFT JOIN biblio_topic bt    ON bt.biblio_id   = b.biblio_id
            LEFT JOIN mst_topic t        ON t.topic_id     = bt.topic_id
            WHERE b.opac_hide = 0
            GROUP BY b.biblio_id
            ORDER BY b.biblio_id
            {$limitSql}
        ");

        $total = count($books);
        $this->info("Mengambil {$total} biblio dari SLiMS. Mulai embedding (jaga rate limit, mohon sabar)...");

        $ok = 0; $err = 0;
        $bar = $this->output->createProgressBar($total);
        $bar->start();

        foreach ($books as $b) {
            try {
                // hapus embedding lama biblio ini -> idempoten walau tanpa --fresh
                DB::table('document_embeddings')
                    ->where('source_type', 'biblio')
                    ->where('source_id', $b->biblio_id)
                    ->delete();

                $chunk  = $this->buildChunkText($b);
                $vector = $this->gemini->embedText($chunk, 'RETRIEVAL_DOCUMENT');

                DB::table('document_embeddings')->insert([
                    'source_type' => 'biblio',
                    'source_id'   => $b->biblio_id,
                    'chunk_text'  => $chunk,
                    'embedding'   => json_encode($vector),
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);

                $ok++;
                usleep(200000); // 0.2 dtk -> ramah rate limit free tier
            } catch (Throwable $e) {
                $err++;
                Log::error('[slims:sync] gagal embed biblio', [
                    'biblio_id' => $b->biblio_id,
                    'msg'       => $e->getMessage(),
                ]);
            }
            $bar->advance();
        }
        $bar->finish();
        $this->newLine(2);

        // 2. Embed library_info LOKAL (FAQ perpustakaan: jam buka, denda, dll — tidak ada di SLiMS)
        $infoOk = 0; $infoErr = 0;
        $infos = DB::table('library_info')->where('is_active', true)->get();
        $this->info("Embedding {$infos->count()} dokumen library_info lokal...");

        foreach ($infos as $info) {
            try {
                DB::table('document_embeddings')
                    ->where('source_type', 'library_info')
                    ->where('source_id', $info->id)
                    ->delete();

                $chunk  = "{$info->title}. {$info->content}";
                $vector = $this->gemini->embedText($chunk, 'RETRIEVAL_DOCUMENT');

                DB::table('document_embeddings')->insert([
                    'source_type' => 'library_info',
                    'source_id'   => $info->id,
                    'chunk_text'  => $chunk,
                    'embedding'   => json_encode($vector),
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);
                $infoOk++;
                usleep(200000);
            } catch (Throwable $e) {
                $infoErr++;
                Log::error('[slims:sync] gagal embed library_info', [
                    'id'  => $info->id,
                    'msg' => $e->getMessage(),
                ]);
            }
        }

        $this->newLine();
        $this->info("SELESAI.");
        $this->line("  Biblio       : {$ok} berhasil, {$err} gagal");
        $this->line("  Library_info : {$infoOk} berhasil, {$infoErr} gagal");
        if ($err > 0 || $infoErr > 0) {
            $this->warn('Ada kegagalan — cek storage/logs/laravel.log (biasanya rate limit/503 Gemini). Jalankan ulang tanpa --fresh untuk melengkapi yang gagal.');
        }

        return self::SUCCESS;
    }

    /**
     * Rakit teks embed dari field SLiMS. Sanitasi tahun. Fallback bila notes kosong.
     */
    private function buildChunkText(object $b): string
    {
        $year    = $this->sanitizeYear($b->publish_year);
        $authors = trim((string) $b->authors) !== '' ? $b->authors : 'Tidak diketahui';

        $parts = [
            "Judul: {$b->title}",
            "Pengarang: {$authors}",
        ];

        if (trim((string) $b->topics) !== '') {
            $parts[] = "Subjek: {$b->topics}";
        }
        if (trim((string) $b->publisher) !== '') {
            $parts[] = "Penerbit: {$b->publisher}" . ($year ? " ({$year})" : '');
        } elseif ($year) {
            $parts[] = "Tahun terbit: {$year}";
        }
        if (!empty($b->isbn_issn)) {
            $parts[] = "ISBN: {$b->isbn_issn}";
        }

        $notes = trim((string) $b->notes);
        if ($notes !== '') {
            $parts[] = "Sinopsis: {$notes}";
        }
        // Jika notes kosong, gabungan judul+pengarang+subjek+penerbit di atas
        // sudah menjadi fallback deskriptif yang cukup untuk retrieval.

        return implode('. ', $parts) . '.';
    }

    /**
     * Ambil 4 digit tahun valid (1500–2099) dari field publish_year yang kotor.
     * Selain itu (mis. "384 hlm ; 20 cm", "-", ISBN nyasar) -> null.
     */
    private function sanitizeYear(?string $raw): ?string
    {
        if (!$raw) {
            return null;
        }
        if (preg_match('/\b(1[5-9]\d{2}|20\d{2})\b/', $raw, $m)) {
            return $m[1];
        }
        return null;
    }
}