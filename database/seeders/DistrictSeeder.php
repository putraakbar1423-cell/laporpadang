<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Seeder untuk 11 kecamatan di Kota Padang
     */
    public function run(): void
    {
        $districts = [
            ['name' => 'Bungus Teluk Kabung', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Koto Tangah', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Kuranji', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Lubuk Begalung', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Lubuk Kilangan', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Nanggalo', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Padang Barat', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Padang Selatan', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Padang Timur', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Padang Utara', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Pauh', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('districts')->insert($districts);
    }
}
