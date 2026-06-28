<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->restrictOnDelete();
            $table->string('title');
            $table->string('series_title')->nullable();
            $table->string('call_number')->nullable();
            $table->string('publisher')->nullable();
            $table->string('physical_description')->nullable();
            $table->string('language')->default('Indonesia');
            $table->string('isbn_issn')->nullable();
            $table->string('classification')->nullable();
            $table->string('content_type')->nullable();
            $table->string('media_type')->nullable();
            $table->string('carrier_type')->nullable();
            $table->string('edition')->nullable();
            $table->string('subject')->nullable();
            $table->text('specific_detail_info')->nullable();
            $table->string('author');
            $table->enum('status', ['tersedia', 'dipinjam', 'maintenance', 'hilang'])->default('tersedia');
            $table->string('image_url')->nullable();
            $table->timestamps();

            $table->index('category_id');
            $table->index('status');
            $table->index('title');
            if (DB::connection()->getDriverName() !== 'sqlite') {
                $table->fullText(['title', 'author', 'subject']);
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
