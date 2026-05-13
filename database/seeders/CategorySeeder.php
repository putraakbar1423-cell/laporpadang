<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Seeder untuk kategori laporan pengaduan masyarakat
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Jalan Rusak',
                'icon' => 'road-damage.png',
                'description' => 'Laporan terkait jalan berlubang, jalan retak, atau kerusakan jalan lainnya',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sampah',
                'icon' => 'trash.png',
                'description' => 'Laporan terkait sampah menumpuk, TPS penuh, atau masalah persampahan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Banjir',
                'icon' => 'flood.png',
                'description' => 'Laporan terkait banjir, genangan air, atau drainase tersumbat',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Lampu Jalan Mati',
                'icon' => 'streetlight.png',
                'description' => 'Laporan terkait lampu jalan mati atau rusak',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Fasilitas Umum Rusak',
                'icon' => 'facility.png',
                'description' => 'Laporan terkait fasilitas umum rusak seperti taman, halte, jembatan, dll',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pohon Tumbang',
                'icon' => 'tree.png',
                'description' => 'Laporan terkait pohon tumbang atau ranting berbahaya',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Saluran Air Tersumbat',
                'icon' => 'drain.png',
                'description' => 'Laporan terkait saluran air atau got tersumbat',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Lainnya',
                'icon' => 'other.png',
                'description' => 'Laporan pengaduan lainnya yang tidak termasuk kategori di atas',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('categories')->insert($categories);
    }
}
