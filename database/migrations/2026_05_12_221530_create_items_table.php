<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabel items — stok fisik eksemplar buku.
 * Di SLiMS, satu biblio bisa punya banyak items (banyak eksemplar).
 *
 * item_code  = kode barcode eksemplar
 * call_number = nomor panggil (rak)
 * item_status_id: 0=Available, 1=Dipinjam, 2=Rusak, 3=Hilang
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id('item_id');
            $table->unsignedBigInteger('biblio_id')->comment('FK ke tabel biblio');
            $table->string('item_code', 20)->unique()->comment('Kode barcode eksemplar');
            $table->string('call_number', 50)->nullable()->comment('Nomor panggil / lokasi rak');
            $table->tinyInteger('item_status_id')->default(0)
                ->comment('0=Available, 1=Dipinjam, 2=Rusak, 3=Hilang');
            $table->date('due_date')->nullable()->comment('Tanggal jatuh tempo jika sedang dipinjam');
            $table->timestamps();

            $table->foreign('biblio_id')
                ->references('biblio_id')
                ->on('biblio')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};