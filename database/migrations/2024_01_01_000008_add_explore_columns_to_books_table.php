<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->string('publication_year', 4)->nullable()->after('edition');
            $table->string('location')->nullable()->after('publication_year');
            $table->string('collection_type')->nullable()->after('location');
            $table->string('gmd_type')->nullable()->after('collection_type');
            $table->text('description')->nullable()->after('specific_detail_info');

            $table->index('publication_year');
            $table->index('location');
            $table->index('collection_type');
            $table->index('gmd_type');
            $table->index('author');
            $table->index('publisher');
        });
    }

    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropIndex(['publication_year']);
            $table->dropIndex(['location']);
            $table->dropIndex(['collection_type']);
            $table->dropIndex(['gmd_type']);
            $table->dropIndex(['author']);
            $table->dropIndex(['publisher']);
            $table->dropColumn([
                'publication_year',
                'location',
                'collection_type',
                'gmd_type',
                'description',
            ]);
        });
    }
};