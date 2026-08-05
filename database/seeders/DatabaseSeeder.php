<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\District;
use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with reference data for Kota Padang:
     * districts, report categories, sample users and sample reports.
     */
    public function run(): void
    {
        // ── Kecamatan Kota Padang ──────────────────────────────────────
        $districts = [
            'Padang Utara', 'Padang Selatan', 'Padang Timur', 'Padang Barat',
            'Padang Tengah', 'Koto Tangah', 'Lubuk Begalung', 'Lubuk Kilangan',
            'Pauh', 'Bungus Teluk Kabung', 'Nanggalo', 'Kuranji',
        ];
        foreach ($districts as $name) {
            District::firstOrCreate(['name' => $name]);
        }

        // ── Kategori laporan ───────────────────────────────────────────
        $categories = [
            ['name' => 'Infrastruktur', 'icon' => 'construction_rounded', 'color' => '#2E7D32', 'description' => 'Jalan rusak, jembatan, trotoar'],
            ['name' => 'Kebersihan', 'icon' => 'delete_outline_rounded', 'color' => '#00A86B', 'description' => 'Sampah, kebersihan lingkungan'],
            ['name' => 'Banjir', 'icon' => 'water_drop_rounded', 'color' => '#1565C0', 'description' => 'Genangan dan banjir'],
            ['name' => 'Lampu Jalan', 'icon' => 'lightbulb_rounded', 'color' => '#F9A825', 'description' => 'Penerangan jalan umum'],
            ['name' => 'Fasilitas Umum', 'icon' => 'location_city_rounded', 'color' => '#6A1B9A', 'description' => 'Taman, bangunan publik'],
            ['name' => 'Lainnya', 'icon' => 'more_horiz_rounded', 'color' => '#607D8B', 'description' => 'Laporan lainnya'],
        ];
        foreach ($categories as $c) {
            Category::firstOrCreate(['name' => $c['name']], $c);
        }

        // ── Users ──────────────────────────────────────────────────────
        $admin = User::firstOrCreate(
            ['email' => 'admin@laporpadang.id'],
            [
                'name' => 'Admin Padang',
                'phone' => '081111111111',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        $user = User::firstOrCreate(
            ['email' => 'user@laporpadang.id'],
            [
                'name' => 'Warga Padang',
                'phone' => '082222222222',
                'password' => Hash::make('user123'),
                'role' => 'user',
            ]
        );

        // ── Sample reports ─────────────────────────────────────────────
        $samples = [
            ['title' => 'Jalan Rusak di Jl. Sudirman', 'category' => 'Infrastruktur', 'district' => 'Padang Barat', 'status' => 'process', 'desc' => 'Terdapat jalan berlubang cukup dalam di depan kantor pos.'],
            ['title' => 'Sampah Menumpuk di Jl. Bagindo Aziz', 'category' => 'Kebersihan', 'district' => 'Padang Selatan', 'status' => 'pending', 'desc' => 'Tumpukan sampah tidak terangkut lebih dari 3 hari.'],
            ['title' => 'Banjir di Kawasan Koto Tangah', 'category' => 'Banjir', 'district' => 'Koto Tangah', 'status' => 'done', 'desc' => 'Genangan setinggi 30cm setelah hujan lebat.'],
            ['title' => 'Lampu Jalan Mati di Jl. Pemuda', 'category' => 'Lampu Jalan', 'district' => 'Padang Utara', 'status' => 'pending', 'desc' => 'Lampu jalan mati sepanjang 200 meter.'],
            ['title' => 'Taman Rusak di Pantai Padang', 'category' => 'Fasilitas Umum', 'district' => 'Padang Barat', 'status' => 'rejected', 'desc' => 'Fasilitas taman rusak dan berbahaya bagi pengunjung.'],
        ];

        foreach ($samples as $i => $s) {
            Report::firstOrCreate(
                ['title' => $s['title'], 'user_id' => $user->id],
                [
                    'user_id' => $user->id,
                    'category_id' => Category::where('name', $s['category'])->first()->id,
                    'district_id' => District::where('name', $s['district'])->first()->id,
                    'title' => $s['title'],
                    'description' => $s['desc'],
                    'address' => $s['district'] . ', Kota Padang',
                    'latitude' => -0.9471 + ($i * 0.001),
                    'longitude' => 100.4172 + ($i * 0.001),
                    'status' => $s['status'],
                ]
            );
        }
    }
}
