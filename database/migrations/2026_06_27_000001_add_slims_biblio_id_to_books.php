<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Menandai asal-usul setiap buku dari SLiMS (biblio_id), agar impor katalog
 * bisa idempoten (updateOrInsert) tanpa menduplikasi saat dijalankan ulang.
 * Buku non-SLiMS (seed lama) tetap NULL.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->unsignedInteger('slims_biblio_id')->nullable()->unique()->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropUnique(['slims_biblio_id']);
            $table->dropColumn('slims_biblio_id');
        });
    }
};