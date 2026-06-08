<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabel biblio — mengikuti struktur SLiMS (Senayan Library Management System).
 * Kolom-kolom utama dipertahankan agar kompatibel saat swap ke data real SLiMS.
 *
 * Kolom SLiMS asli yang dipakai:
 *   biblio_id, title, sor_title, author_main, publish_year, isbn_issn, notes (sinopsis)
 * Kolom tambahan untuk RAG:
 *   summary_text → teks gabungan untuk di-embed ke vector store
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('biblio', function (Blueprint $table) {
            // --- Kolom utama SLiMS ---
            $table->id('biblio_id');
            $table->string('title', 300);
            $table->string('sor_title', 300)->nullable()->comment('Sort title / judul alternatif');
            $table->string('author_main', 200)->nullable()->comment('Pengarang utama');
            $table->string('publisher_id')->nullable()->comment('Nama penerbit');
            $table->string('publish_place', 100)->nullable();
            $table->year('publish_year')->nullable();
            $table->string('isbn_issn', 30)->nullable();
            $table->string('classification', 50)->nullable()->comment('Kode DDC / klasifikasi');
            $table->string('subject', 300)->nullable()->comment('Kata kunci / subjek');
            $table->text('notes')->nullable()->comment('Sinopsis / catatan buku');
            $table->string('language_id', 10)->nullable()->default('id');
            $table->tinyInteger('available')->default(1)->comment('1=aktif, 0=tidak aktif');

            // --- Kolom tambahan untuk RAG ---
            $table->text('summary_text')->nullable()
                ->comment('Teks gabungan untuk embedding: title + author + subject + notes');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('biblio');
    }
};