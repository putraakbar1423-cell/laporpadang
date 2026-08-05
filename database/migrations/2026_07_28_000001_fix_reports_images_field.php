<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Fix: Ubah field 'image' (singular) menjadi 'images' (plural JSON array)
     * Agar support multiple images per report
     */
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            // Drop old 'image' field (singular)
            if (Schema::hasColumn('reports', 'image')) {
                $table->dropColumn('image');
            }
            
            // Add new 'images' field (plural, JSON array)
            if (!Schema::hasColumn('reports', 'images')) {
                $table->json('images')->nullable()->after('address');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            // Revert back to singular 'image'
            if (Schema::hasColumn('reports', 'images')) {
                $table->dropColumn('images');
            }
            
            if (!Schema::hasColumn('reports', 'image')) {
                $table->string('image')->nullable();
            }
        });
    }
};
