<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabel library_info — menyimpan informasi statis perpustakaan.
 * Ini adalah "knowledge base" untuk RAG non-buku:
 *   jam operasional, peraturan peminjaman, denda, kontak, dll.
 *
 * category: pengelompokan dokumen (jam_operasional, peraturan, denda, kontak, fasilitas)
 * content:  teks lengkap yang akan di-embed dan dipakai sebagai konteks chatbot
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('library_info', function (Blueprint $table) {
            $table->id();
            $table->string('category', 50)
                ->comment('jam_operasional | peraturan | denda | kontak | fasilitas | lainnya');
            $table->string('title', 200)->comment('Judul / label dokumen');
            $table->text('content')->comment('Isi informasi — teks lengkap untuk RAG');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('library_info');
    }
};