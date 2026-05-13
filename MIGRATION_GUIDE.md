# 📋 Panduan Migration Database LaporPadang

## 🎯 Ringkasan

Migration database lengkap untuk aplikasi **LaporPadang** - Sistem Pelaporan dan Pengaduan Masyarakat Kota Padang.

### ✅ Yang Sudah Dibuat

1. ✅ 8 Migration Files (lengkap dengan foreign key dan relasi)
2. ✅ 2 Seeder Files (CategorySeeder & DistrictSeeder)
3. ✅ Dokumentasi ERD dan Struktur Database
4. ✅ Best Practice Laravel terbaru

---

## 📁 File Migration yang Dibuat

```
database/migrations/
├── 2026_05_13_105405_create_users_table.php
├── 2026_05_13_105407_create_categories_table.php
├── 2026_05_13_105410_create_comments_table.php
├── 2026_05_13_105413_create_reports_table.php
├── 2026_05_13_105421_create_districts_table.php
├── 2026_05_13_105423_create_report_images_table.php
├── 2026_05_13_105427_create_activity_logs_table.php
└── 2026_05_13_105431_create_notifications_table.php
```

---

## 🚀 Cara Menjalankan Migration

### 1. Konfigurasi Database

Edit file `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laporpadang
DB_USERNAME=root
DB_PASSWORD=
```

### 2. Buat Database

```bash
# Masuk ke MySQL
mysql -u root -p

# Buat database
CREATE DATABASE laporpadang CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

### 3. Jalankan Migration

```bash
# Jalankan semua migration
php artisan migrate

# Output yang diharapkan:
# Migrating: 2026_05_13_105405_create_users_table
# Migrated:  2026_05_13_105405_create_users_table (XX.XXms)
# Migrating: 2026_05_13_105407_create_categories_table
# Migrated:  2026_05_13_105407_create_categories_table (XX.XXms)
# ... dst
```

### 4. Jalankan Seeder (Opsional)

```bash
# Jalankan seeder untuk categories dan districts
php artisan db:seed --class=CategorySeeder
php artisan db:seed --class=DistrictSeeder

# Atau jalankan semua seeder sekaligus
php artisan db:seed
```

---

## 🗂️ Struktur Tabel

### Tabel Utama

| Tabel | Deskripsi | Relasi |
|-------|-----------|--------|
| **users** | Data pengguna (admin & masyarakat) | Has Many: reports, comments, notifications |
| **categories** | Kategori laporan | Has Many: reports |
| **districts** | Kecamatan Kota Padang | Has Many: reports |
| **reports** | Laporan pengaduan (tabel utama) | Belongs To: user, category, district<br>Has Many: report_images, comments |
| **report_images** | Multiple foto per laporan | Belongs To: report |
| **comments** | Komentar admin | Belongs To: report, user |
| **notifications** | Notifikasi pengguna | Belongs To: user |
| **activity_logs** | Log aktivitas sistem | Belongs To: user |

---

## 🔑 Fitur Database

### ✅ Foreign Key dengan Cascade Delete

```php
// Contoh di tabel reports
$table->foreignId('user_id')->constrained('users')->onDelete('cascade');
$table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
$table->foreignId('district_id')->constrained('districts')->onDelete('cascade');
```

**Artinya:**
- Jika user dihapus → semua laporan miliknya ikut terhapus
- Jika category dihapus → semua laporan dengan category tersebut ikut terhapus
- Jika district dihapus → semua laporan di district tersebut ikut terhapus

### ✅ Soft Delete

Hanya tabel `reports` yang menggunakan soft delete:

```php
$table->softDeletes();
```

**Keuntungan:**
- Data tidak benar-benar dihapus dari database
- Bisa di-restore kapan saja
- Berguna untuk audit trail

### ✅ Enum Status

Status laporan menggunakan enum untuk membatasi nilai:

```php
$table->enum('status', ['pending', 'process', 'done', 'rejected'])->default('pending');
```

**Status Flow:**
1. `pending` → Laporan baru masuk
2. `process` → Sedang ditindaklanjuti
3. `done` → Selesai ditangani
4. `rejected` → Ditolak (tidak valid)

### ✅ GPS Coordinates

Koordinat GPS menggunakan decimal dengan presisi tinggi:

```php
$table->decimal('latitude', 10, 8)->nullable();  // -90.00000000 to 90.00000000
$table->decimal('longitude', 11, 8)->nullable(); // -180.00000000 to 180.00000000
```

---

## 📊 Data Awal (Seeder)

### Categories (8 Kategori)

1. Jalan Rusak
2. Sampah
3. Banjir
4. Lampu Jalan Mati
5. Fasilitas Umum Rusak
6. Pohon Tumbang
7. Saluran Air Tersumbat
8. Lainnya

### Districts (11 Kecamatan Kota Padang)

1. Bungus Teluk Kabung
2. Koto Tangah
3. Kuranji
4. Lubuk Begalung
5. Lubuk Kilangan
6. Nanggalo
7. Padang Barat
8. Padang Selatan
9. Padang Timur
10. Padang Utara
11. Pauh

---

## 🔧 Troubleshooting

### Error: Foreign Key Constraint

**Masalah:**
```
SQLSTATE[HY000]: General error: 1215 Cannot add foreign key constraint
```

**Solusi:**
1. Pastikan tabel parent sudah dibuat terlebih dahulu
2. Pastikan tipe data foreign key sama dengan primary key
3. Jalankan migration dengan urutan yang benar

### Error: Table Already Exists

**Masalah:**
```
SQLSTATE[42S01]: Base table or view already exists
```

**Solusi:**
```bash
# Rollback migration
php artisan migrate:rollback

# Atau reset semua migration
php artisan migrate:reset

# Lalu jalankan ulang
php artisan migrate
```

### Error: Database Connection

**Masalah:**
```
SQLSTATE[HY000] [2002] Connection refused
```

**Solusi:**
1. Pastikan MySQL sudah running
2. Cek konfigurasi `.env`
3. Pastikan database sudah dibuat

---

## 🎓 Langkah Selanjutnya

Setelah migration selesai, Anda bisa lanjut ke:

### 1. Membuat Model dengan Relasi

```bash
php artisan make:model User
php artisan make:model Category
php artisan make:model District
php artisan make:model Report
php artisan make:model ReportImage
php artisan make:model Comment
php artisan make:model Notification
php artisan make:model ActivityLog
```

### 2. Membuat Controller

```bash
php artisan make:controller Api/AuthController
php artisan make:controller Api/ReportController
php artisan make:controller Api/CategoryController
php artisan make:controller Api/DistrictController
php artisan make:controller Api/CommentController
php artisan make:controller Api/NotificationController
```

### 3. Membuat Request Validation

```bash
php artisan make:request StoreReportRequest
php artisan make:request UpdateReportRequest
php artisan make:request LoginRequest
php artisan make:request RegisterRequest
```

### 4. Membuat API Resource

```bash
php artisan make:resource ReportResource
php artisan make:resource UserResource
php artisan make:resource CategoryResource
php artisan make:resource CommentResource
```

### 5. Setup Laravel Sanctum (Authentication)

```bash
php artisan install:api
```

---

## 📝 Catatan Penting

1. **Backup Database**: Selalu backup database sebelum menjalankan migration di production
2. **Testing**: Test migration di local/development environment terlebih dahulu
3. **Rollback Plan**: Siapkan rencana rollback jika terjadi masalah
4. **Documentation**: Update dokumentasi jika ada perubahan struktur database
5. **Version Control**: Commit migration files ke Git

---

## 📚 Referensi

- [Laravel Migration Documentation](https://laravel.com/docs/11.x/migrations)
- [Laravel Eloquent Relationships](https://laravel.com/docs/11.x/eloquent-relationships)
- [Laravel Database Seeding](https://laravel.com/docs/11.x/seeding)
- [MySQL Data Types](https://dev.mysql.com/doc/refman/8.0/en/data-types.html)

---

## 👨‍💻 Developer Notes

**Project:** LaporPadang - Aplikasi Pelaporan Masyarakat Kota Padang  
**Tech Stack:** Laravel 11 + MySQL + Flutter  
**Database Engine:** InnoDB (default MySQL)  
**Character Set:** utf8mb4_unicode_ci  
**Created:** 13 Mei 2026  

---

**Happy Coding! 🚀**
