<?php

namespace App\Console\Commands;

use App\Models\Kategori;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * slims:import-catalog
 * -----------------------------------------------------------------------------
 * Mengimpor (sync) biblio dari SLiMS ke tabel `books` lokal yang sudah dipakai
 * UI katalog (ExploreController, SearchService, semua blade). Dengan begitu
 * tampilan web menampilkan koleksi asli SLiMS TANPA mengubah satu pun controller,
 * service, atau view.
 *
 * Pemetaan kunci yang ditemukan dari data asli SMAMDA:
 *   - category_id (WAJIB, FK) <- diturunkan dari kelas utama DDC (digit pertama
 *     classification). Cakupan DDC = 100%, jadi setiap buku pasti termap.
 *   - author/subject ternormalisasi M:N <- dirakit via GROUP_CONCAT.
 *   - publisher/language = FK <- di-resolve via mst_publisher / mst_language.
 *   - status (enum) <- diturunkan dari ketersediaan eksemplar (item + loan).
 *   - physical_description <- biblio.collation (mis. "384 hlm ; 20 cm").
 *
 * Idempoten: keyed pada books.slims_biblio_id (lihat migrasi pendamping).
 */
class SlimsImportCatalogCommand extends Command
{
    protected $signature = 'slims:import-catalog
                            {--fresh : Hapus semua books (dan borrowings) lalu impor ulang dari nol}';

    protected $description = 'Impor/sync biblio dari SLiMS ke tabel books lokal agar katalog web menampilkan koleksi asli.';

    /** Peta digit DDC -> [nama kategori, slug]. */
    private const DDC = [
        '0' => ['Karya Umum & Ilmu Komputer', 'karya-umum'],
        '1' => ['Filsafat & Psikologi',        'filsafat-psikologi'],
        '2' => ['Agama',                        'agama'],
        '3' => ['Ilmu Sosial',                  'ilmu-sosial'],
        '4' => ['Bahasa',                       'bahasa'],
        '5' => ['Sains & Matematika',           'sains'],
        '6' => ['Teknologi & Ilmu Terapan',     'teknologi'],
        '7' => ['Seni & Rekreasi',              'seni-rekreasi'],
        '8' => ['Kesusastraan',                 'sastra'],
        '9' => ['Sejarah & Geografi',           'sejarah-geografi'],
    ];

    public function handle(): int
    {
        try {
            DB::connection('slims')->getPdo();
        } catch (Throwable $e) {
            $this->error('Gagal konek ke database SLiMS (koneksi "slims"). Detail: ' . $e->getMessage());
            return self::FAILURE;
        }

        // 1. Pastikan kategori DDC + fallback "Lainnya" ada; bangun peta digit -> id
        $catId = [];
        foreach (self::DDC as $digit => [$name, $slug]) {
            $catId[$digit] = Kategori::firstOrCreate(['slug' => $slug], ['name' => $name])->id;
        }
        $catId['_'] = Kategori::firstOrCreate(['slug' => 'lainnya'], ['name' => 'Lainnya'])->id;

        // 2. Opsional bersih total
        if ($this->option('fresh')) {
            if (! $this->confirm('--fresh akan MENGHAPUS semua data books dan borrowings (termasuk catatan peminjaman uji). Lanjut?')) {
                $this->warn('Dibatalkan.');
                return self::SUCCESS;
            }
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            DB::table('borrowings')->truncate();
            DB::table('books')->truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
            $this->warn('books & borrowings dikosongkan.');
        }

        // 3. Tarik biblio dari SLiMS (konten dirakit + hitung ketersediaan)
        $rows = DB::connection('slims')->select("
            SELECT b.biblio_id, b.title, b.series_title, b.call_number, b.edition, b.isbn_issn,
                   b.classification, b.collation AS physical_description, b.spec_detail_info,
                   COALESCE(p.publisher_name, '')          AS publisher,
                   COALESCE(lang.language_name, 'Indonesia') AS language,
                   COALESCE(GROUP_CONCAT(DISTINCT a.author_name ORDER BY ba.level SEPARATOR '; '), '') AS authors,
                   COALESCE(GROUP_CONCAT(DISTINCT t.topic SEPARATOR ', '), '') AS subjects,
                   (SELECT COUNT(*) FROM item i WHERE i.biblio_id = b.biblio_id) AS total_item,
                   (SELECT COUNT(*) FROM item i
                      WHERE i.biblio_id = b.biblio_id
                        AND (i.item_status_id = '0' OR i.item_status_id = '' OR i.item_status_id IS NULL)
                        AND NOT EXISTS (SELECT 1 FROM loan l WHERE l.item_code = i.item_code AND l.is_return = 0)
                   ) AS ready_item,
                   (SELECT COUNT(*) FROM item i JOIN loan l ON l.item_code = i.item_code WHERE i.biblio_id = b.biblio_id) AS loan_count
            FROM biblio b
            LEFT JOIN mst_publisher p   ON p.publisher_id = b.publisher_id
            LEFT JOIN mst_language lang  ON lang.language_id = b.language_id
            LEFT JOIN biblio_author ba   ON ba.biblio_id   = b.biblio_id
            LEFT JOIN mst_author a        ON a.author_id    = ba.author_id
            LEFT JOIN biblio_topic bt     ON bt.biblio_id   = b.biblio_id
            LEFT JOIN mst_topic t         ON t.topic_id     = bt.topic_id
            WHERE b.opac_hide = 0
            GROUP BY b.biblio_id
            ORDER BY b.biblio_id
        ");

        $total = count($rows);
        $this->info("Mengimpor {$total} biblio dari SLiMS ke tabel books...");
        $bar = $this->output->createProgressBar($total);
        $bar->start();

        $ok = 0;
        foreach ($rows as $r) {
            $digit = preg_match('/^\s*([0-9])/', (string) $r->classification, $m) ? $m[1] : '_';

            $tot   = (int) $r->total_item;
            $ready = (int) $r->ready_item;
            $status = $tot === 0 ? 'maintenance' : ($ready > 0 ? 'tersedia' : 'dipinjam');

            DB::table('books')->updateOrInsert(
                ['slims_biblio_id' => $r->biblio_id],
                [
                    'category_id'          => $catId[$digit] ?? $catId['_'],
                    'title'                => $this->cut($r->title, 255) ?: '(Tanpa judul)',
                    'author'               => $this->cut($r->authors, 255) ?: 'Tidak diketahui',
                    'publisher'            => $this->cut($r->publisher, 255) ?: null,
                    'series_title'         => $this->cut($r->series_title, 255) ?: null,
                    'call_number'          => $this->cut($r->call_number, 50) ?: null,
                    'edition'              => $this->cut($r->edition, 255) ?: null,
                    'isbn_issn'            => $this->cut($r->isbn_issn, 255) ?: null,
                    'classification'       => $this->cut($r->classification, 255) ?: null,
                    'subject'              => $this->cut($r->subjects, 255) ?: null,
                    'physical_description' => $this->cut($r->physical_description, 255) ?: null,
                    'specific_detail_info' => $r->spec_detail_info ?: null,
                    'language'             => $this->cut($r->language, 255) ?: 'Indonesia',
                    'status'               => $status,
                    'slims_loan_count'     => (int) $r->loan_count,
                    'image_url'            => null, 
                    'updated_at'           => now(),
                    'created_at'           => now(),
                ]
            );
            $ok++;
            $bar->advance();
        }
        $bar->finish();
        $this->newLine(2);

        $this->info("SELESAI. {$ok} buku tersinkron ke katalog.");
        $this->line('Kategori (DDC) yang dipakai: ' . implode(', ', array_map(fn ($d) => $d[1], self::DDC)) . ', lainnya.');
        $this->line('Buka halaman /explore — katalog kini menampilkan koleksi asli SLiMS.');

        return self::SUCCESS;
    }

    private function cut(?string $s, int $len): ?string
    {
        $s = trim((string) $s);
        return $s === '' ? null : mb_substr($s, 0, $len);
    }
}