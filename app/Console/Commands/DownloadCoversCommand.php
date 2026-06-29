<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Throwable;

/**
 * covers:download
 * -----------------------------------------------------------------------------
 * Menarik file cover dari server SLiMS (sekali jalan) ke storage aplikasi,
 * lalu mengisi books.image_url -> 'covers/<file>'. Setelah ini cover MANDIRI,
 * tidak bergantung pada server SLiMS (aman walau SLiMS dimatikan).
 *
 * HANYA mengubah kolom image_url. Tidak menyentuh judul/pengarang/status,
 * jadi bukan "re-import katalog".
 *
 * Sumber: kolom biblio.image (nama file) disajikan SLiMS via
 *   <base>/lib/minigalnano/createthumb.php?filename=images/docs/<file>&width=<w>
 *
 * Prasyarat:
 *   - .env: SLIMS_COVER_BASE_URL (mis. https://alqalamlibsmamda.com)
 *   - php artisan storage:link  (agar storage/app/public terekspos di /storage)
 */
class DownloadCoversCommand extends Command
{
    protected $signature = 'covers:download
                            {--limit= : Batasi jumlah cover (uji coba)}
                            {--width=600 : Lebar thumbnail yang diminta dari SLiMS}
                            {--force : Unduh ulang walau file sudah ada}';

    protected $description = 'Unduh cover buku dari SLiMS ke storage aplikasi dan isi books.image_url (sekali jalan).';

    public function handle(): int
    {
        $base = rtrim((string) config('app.slims_cover_base_url', env('SLIMS_COVER_BASE_URL', '')), '/');
        if ($base === '') {
            $this->error('SLIMS_COVER_BASE_URL belum diisi di .env (mis. https://alqalamlibsmamda.com).');
            return self::FAILURE;
        }

        try {
            DB::connection('slims')->getPdo();
        } catch (Throwable $e) {
            $this->error('Gagal konek ke database SLiMS (koneksi "slims"). Detail: ' . $e->getMessage());
            return self::FAILURE;
        }

        $width = (int) $this->option('width') ?: 600;
        $force = (bool) $this->option('force');

        // Peta biblio_id -> nama file cover (hanya yang punya)
        $images = DB::connection('slims')->table('biblio')
            ->whereNotNull('image')->where('image', '<>', '')
            ->pluck('image', 'biblio_id');

        $books = DB::table('books')
            ->whereNotNull('slims_biblio_id')
            ->select('id', 'slims_biblio_id', 'image_url')
            ->get();

        // Hanya proses buku yang punya file cover di SLiMS
        $targets = $books->filter(fn ($b) => isset($images[$b->slims_biblio_id]));
        if ($limit = $this->option('limit')) {
            $targets = $targets->take((int) $limit);
        }

        $this->info("Buku dengan cover di SLiMS: {$targets->count()} (dari {$books->count()} buku SLiMS).");
        $bar = $this->output->createProgressBar($targets->count());
        $bar->start();

        $ok = 0; $skip = 0; $fail = 0;
        foreach ($targets as $b) {
            $file      = $images[$b->slims_biblio_id];
            $localPath = 'covers/' . $file;

            // Sudah ada -> cukup pastikan image_url terisi
            if (! $force && Storage::disk('public')->exists($localPath)) {
                if ($b->image_url !== $localPath) {
                    DB::table('books')->where('id', $b->id)->update(['image_url' => $localPath]);
                }
                $skip++;
                $bar->advance();
                continue;
            }

            try {
                $url = $base . '/lib/minigalnano/createthumb.php?filename='
                     . rawurlencode('images/docs/' . $file) . '&width=' . $width;

                $resp = Http::timeout(25)->retry(2, 1500)->get($url);

                $ctype = (string) $resp->header('Content-Type');
                // Validasi: harus gambar & ukuran wajar (createthumb kadang balas placeholder kecil)
                if ($resp->successful() && str_starts_with($ctype, 'image') && strlen($resp->body()) > 1024) {
                    Storage::disk('public')->put($localPath, $resp->body());
                    DB::table('books')->where('id', $b->id)->update(['image_url' => $localPath]);
                    $ok++;
                } else {
                    $fail++;
                }
            } catch (Throwable $e) {
                $fail++;
            }

            usleep(120000); // jeda kecil agar tidak membanjiri server SLiMS
            $bar->advance();
        }
        $bar->finish();
        $this->newLine(2);

        $this->info('SELESAI.');
        $this->line("  Cover terunduh : {$ok}");
        $this->line("  Sudah ada      : {$skip}");
        $this->line("  Gagal/kosong   : {$fail}");
        $this->newLine();
        $this->line('Buku tanpa cover di SLiMS akan memakai placeholder (asset images/default-book.png).');
        if ($fail > 0) {
            $this->warn('Sebagian gagal — bisa jalankan ulang (resumable, hanya yang belum terunduh).');
        }

        return self::SUCCESS;
    }
}