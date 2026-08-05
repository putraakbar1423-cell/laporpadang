<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Make `district_id` nullable so reports can be created without a district
     * (the Flutter client only sends title, category, location and description).
     */
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->foreignId('district_id')
                ->nullable()
                ->change();
        });
    }

    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->foreignId('district_id')
                ->constrained('districts')
                ->onDelete('cascade')
                ->change();
        });
    }
};
