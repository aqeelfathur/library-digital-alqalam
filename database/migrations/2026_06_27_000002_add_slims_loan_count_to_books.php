<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Menyimpan total sirkulasi (jumlah peminjaman sepanjang waktu) per buku
 * yang ditarik dari riwayat `loan` SLiMS. Dipakai untuk mengurutkan
 * "Koleksi Populer" di Beranda berdasarkan data peminjaman ASLI.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->unsignedInteger('slims_loan_count')->default(0)->after('slims_biblio_id');
        });
    }

    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn('slims_loan_count');
        });
    }
};