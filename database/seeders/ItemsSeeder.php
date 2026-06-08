<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * ItemsSeeder — Dummy stok eksemplar buku.
 *
 * Setiap buku bisa punya 1–3 eksemplar dengan status berbeda.
 * item_status_id: 0=Available, 1=Dipinjam, 2=Rusak, 3=Hilang
 *
 * Cara jalankan:
 *   php artisan db:seed --class=ItemsSeeder
 *   (pastikan BiblioSeeder sudah jalan duluan)
 */
class ItemsSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil semua biblio_id yang sudah ada
        $biblioIds = DB::table('biblio')->pluck('biblio_id')->toArray();

        $items = [];
        $counter = 1;

        // Pola status per buku: variasikan supaya ada yang dipinjam, ada yang available
        $statusPatterns = [
            [0],          // 1 eksemplar, available
            [0, 0],       // 2 eksemplar, keduanya available
            [0, 1],       // 2 eksemplar, 1 available 1 dipinjam
            [0, 0, 1],    // 3 eksemplar, 2 available 1 dipinjam
            [1],          // 1 eksemplar, sedang dipinjam
        ];

        foreach ($biblioIds as $index => $biblioId) {
            $pattern = $statusPatterns[$index % count($statusPatterns)];

            foreach ($pattern as $exIdx => $status) {
                $itemCode = sprintf('SMAMDA-%04d-%s', $biblioId, chr(65 + $exIdx)); // SMAMDA-0001-A
                $dueDate  = $status === 1 ? now()->addDays(rand(1, 14))->toDateString() : null;

                $items[] = [
                    'biblio_id'      => $biblioId,
                    'item_code'      => $itemCode,
                    'call_number'    => sprintf('%s/%04d/%s', '813', $biblioId, chr(65 + $exIdx)),
                    'item_status_id' => $status,
                    'due_date'       => $dueDate,
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ];
                $counter++;
            }
        }

        DB::table('items')->insert($items);
        $this->command->info('✅ ItemsSeeder: ' . count($items) . ' eksemplar berhasil dimasukkan.');
    }
}