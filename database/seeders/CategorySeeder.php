<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Infrastruktur',
                'description' => 'Laporan terkait infrastruktur jalan, jembatan, dan bangunan publik',
                'icon' => 'construction',
            ],
            [
                'name' => 'Kebersihan',
                'description' => 'Laporan terkait kebersihan lingkungan dan sampah',
                'icon' => 'cleaning_services',
            ],
            [
                'name' => 'Lalu Lintas',
                'description' => 'Laporan terkait kemacetan, rambu lalu lintas, dan kecelakaan',
                'icon' => 'traffic',
            ],
            [
                'name' => 'Penerangan',
                'description' => 'Laporan terkait lampu jalan dan penerangan public',
                'icon' => 'light_mode',
            ],
            [
                'name' => 'Banjir',
                'description' => 'Laporan terkait banjir dan drainase',
                'icon' => 'water',
            ],
            [
                'name' => 'Fasilitas Umum',
                'description' => 'Laporan terkait fasilitas umum seperti taman, toilet umum, dll',
                'icon' => 'place',
            ],
            [
                'name' => 'Lainnya',
                'description' => 'Kategori lainnya yang tidak termasuk di atas',
                'icon' => 'more_horiz',
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['name' => $category['name']],
                $category
            );
        }

        $this->command->info('✓ Categories seeded successfully!');
    }
}
