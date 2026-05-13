# Struktur Database LaporPadang

## ERD (Entity Relationship Diagram)

```
┌─────────────────┐
│     USERS       │
├─────────────────┤
│ id (PK)         │
│ name            │
│ email (unique)  │
│ phone           │
│ address         │
│ photo           │
│ role (enum)     │
│ password        │
│ remember_token  │
│ timestamps      │
└────────┬────────┘
         │
         │ 1:N (user membuat banyak laporan)
         │
         ├──────────────────────────────────────┐
         │                                      │
         │                                      │
┌────────▼────────┐                    ┌───────▼────────┐
│   REPORTS       │                    │  NOTIFICATIONS │
├─────────────────┤                    ├────────────────┤
│ id (PK)         │                    │ id (PK)        │
│ user_id (FK)    │                    │ user_id (FK)   │
│ category_id(FK) │                    │ title          │
│ district_id(FK) │                    │ message        │
│ title           │                    │ is_read        │
│ description     │                    │ timestamps     │
│ image           │                    └────────────────┘
│ latitude        │
│ longitude       │         ┌──────────────────┐
│ address         │         │   CATEGORIES     │
│ status (enum)   │         ├──────────────────┤
│ admin_note      │         │ id (PK)          │
│ timestamps      │◄────────┤ name             │
│ soft_deletes    │   N:1   │ icon             │
└────────┬────────┘         │ description      │
         │                  │ timestamps       │
         │                  └──────────────────┘
         │
         │ 1:N (laporan punya banyak foto)
         │
         ├──────────────────────────────────────┐
         │                                      │
         │                                      │
┌────────▼────────┐                    ┌───────▼────────┐
│ REPORT_IMAGES   │                    │   COMMENTS     │
├─────────────────┤                    ├────────────────┤
│ id (PK)         │                    │ id (PK)        │
│ report_id (FK)  │                    │ report_id (FK) │
│ image           │                    │ user_id (FK)   │
│ timestamps      │                    │ comment        │
└─────────────────┘                    │ timestamps     │
                                       └────────────────┘

         ┌──────────────────┐
         │   DISTRICTS      │
         ├──────────────────┤
         │ id (PK)          │
         │ name             │
         │ timestamps       │
         └────────┬─────────┘
                  │
                  │ 1:N (kecamatan punya banyak laporan)
                  │
                  └──────────► REPORTS

┌─────────────────┐
│ ACTIVITY_LOGS   │
├─────────────────┤
│ id (PK)         │
│ user_id (FK)    │
│ activity        │
│ description     │
│ ip_address      │
│ timestamps      │
└─────────────────┘
```

## Penjelasan Tabel

### 1. **users**
Menyimpan data pengguna aplikasi (admin dan masyarakat).

**Kolom:**
- `id`: Primary key
- `name`: Nama lengkap pengguna
- `email`: Email unik untuk login
- `phone`: Nomor telepon (opsional)
- `address`: Alamat lengkap (opsional)
- `photo`: Path foto profil (opsional)
- `role`: Enum ['admin', 'user'] - default 'user'
- `password`: Password terenkripsi
- `remember_token`: Token untuk remember me
- `timestamps`: created_at, updated_at

**Relasi:**
- Has Many: reports, comments, notifications, activity_logs

---

### 2. **categories**
Menyimpan kategori laporan pengaduan.

**Kolom:**
- `id`: Primary key
- `name`: Nama kategori (contoh: Jalan Rusak, Sampah, Banjir)
- `icon`: Path icon kategori (opsional)
- `description`: Deskripsi kategori (opsional)
- `timestamps`: created_at, updated_at

**Relasi:**
- Has Many: reports

**Contoh Data:**
- Jalan Rusak
- Sampah Menumpuk
- Banjir
- Lampu Jalan Mati
- Fasilitas Umum Rusak

---

### 3. **districts**
Menyimpan data kecamatan di Kota Padang.

**Kolom:**
- `id`: Primary key
- `name`: Nama kecamatan
- `timestamps`: created_at, updated_at

**Relasi:**
- Has Many: reports

**Contoh Data (11 Kecamatan di Kota Padang):**
- Bungus Teluk Kabung
- Koto Tangah
- Kuranji
- Lubuk Begalung
- Lubuk Kilangan
- Nanggalo
- Padang Barat
- Padang Selatan
- Padang Timur
- Padang Utara
- Pauh

---

### 4. **reports** (Tabel Utama)
Menyimpan laporan pengaduan masyarakat.

**Kolom:**
- `id`: Primary key
- `user_id`: Foreign key ke users (cascade delete)
- `category_id`: Foreign key ke categories (cascade delete)
- `district_id`: Foreign key ke districts (cascade delete)
- `title`: Judul laporan
- `description`: Deskripsi detail laporan
- `image`: Path foto utama laporan (opsional)
- `latitude`: Koordinat GPS latitude (decimal 10,8)
- `longitude`: Koordinat GPS longitude (decimal 11,8)
- `address`: Alamat lokasi kejadian
- `status`: Enum ['pending', 'process', 'done', 'rejected'] - default 'pending'
- `admin_note`: Catatan dari admin (opsional)
- `timestamps`: created_at, updated_at
- `soft_deletes`: deleted_at (untuk soft delete)

**Relasi:**
- Belongs To: user, category, district
- Has Many: report_images, comments

**Status Flow:**
1. `pending`: Laporan baru masuk, menunggu verifikasi
2. `process`: Laporan sedang ditindaklanjuti
3. `done`: Laporan selesai ditangani
4. `rejected`: Laporan ditolak (tidak valid/spam)

---

### 5. **report_images**
Menyimpan multiple foto untuk satu laporan.

**Kolom:**
- `id`: Primary key
- `report_id`: Foreign key ke reports (cascade delete)
- `image`: Path file foto
- `timestamps`: created_at, updated_at

**Relasi:**
- Belongs To: report

**Catatan:**
Satu laporan bisa memiliki banyak foto untuk dokumentasi yang lebih lengkap.

---

### 6. **comments**
Menyimpan komentar/tanggapan admin terhadap laporan.

**Kolom:**
- `id`: Primary key
- `report_id`: Foreign key ke reports (cascade delete)
- `user_id`: Foreign key ke users (cascade delete)
- `comment`: Isi komentar
- `timestamps`: created_at, updated_at

**Relasi:**
- Belongs To: report, user

**Catatan:**
Admin dapat memberikan update progress atau penjelasan melalui komentar.

---

### 7. **notifications**
Menyimpan notifikasi untuk pengguna.

**Kolom:**
- `id`: Primary key
- `user_id`: Foreign key ke users (cascade delete)
- `title`: Judul notifikasi
- `message`: Isi pesan notifikasi
- `is_read`: Boolean status sudah dibaca (default false)
- `timestamps`: created_at, updated_at

**Relasi:**
- Belongs To: user

**Trigger Notifikasi:**
- Laporan berhasil dibuat
- Status laporan berubah
- Admin memberikan komentar
- Laporan selesai ditangani

---

### 8. **activity_logs**
Mencatat aktivitas penting sistem untuk audit trail.

**Kolom:**
- `id`: Primary key
- `user_id`: Foreign key ke users (nullable, set null on delete)
- `activity`: Jenis aktivitas
- `description`: Deskripsi detail aktivitas (opsional)
- `ip_address`: IP address pengguna (varchar 45 untuk IPv6)
- `timestamps`: created_at, updated_at

**Relasi:**
- Belongs To: user (nullable)

**Contoh Aktivitas:**
- User login
- User membuat laporan
- Admin mengubah status laporan
- Admin menghapus laporan
- User mengubah profil

---

## Urutan Migration

Migration akan dijalankan sesuai timestamp filename:

1. `2026_05_13_105405_create_users_table.php` ✅
2. `2026_05_13_105407_create_categories_table.php` ✅
3. `2026_05_13_105421_create_districts_table.php` ✅
4. `2026_05_13_105413_create_reports_table.php` ✅ (depends on: users, categories, districts)
5. `2026_05_13_105410_create_comments_table.php` ✅ (depends on: reports, users)
6. `2026_05_13_105423_create_report_images_table.php` ✅ (depends on: reports)
7. `2026_05_13_105427_create_activity_logs_table.php` ✅ (depends on: users)
8. `2026_05_13_105431_create_notifications_table.php` ✅ (depends on: users)

---

## Foreign Key Constraints

Semua foreign key menggunakan `constrained()` dan `onDelete('cascade')` kecuali:
- `activity_logs.user_id`: menggunakan `onDelete('set null')` karena log harus tetap ada meski user dihapus

**Cascade Delete Behavior:**
- Jika user dihapus → semua reports, comments, notifications miliknya ikut terhapus
- Jika report dihapus → semua report_images dan comments terkait ikut terhapus
- Jika category dihapus → semua reports dengan category tersebut ikut terhapus
- Jika district dihapus → semua reports di district tersebut ikut terhapus

---

## Soft Delete

Hanya tabel `reports` yang menggunakan soft delete (`softDeletes()`).

**Alasan:**
- Laporan yang dihapus perlu bisa di-restore
- Data historis laporan penting untuk analisis
- Admin bisa melihat laporan yang sudah dihapus

---

## Index Recommendations

Untuk performa optimal, pertimbangkan menambahkan index pada:

```php
// Di migration reports
$table->index('status');
$table->index('created_at');
$table->index(['user_id', 'status']);

// Di migration notifications
$table->index(['user_id', 'is_read']);

// Di migration activity_logs
$table->index('created_at');
$table->index('user_id');
```

---

## Cara Menjalankan Migration

```bash
# Jalankan semua migration
php artisan migrate

# Rollback migration terakhir
php artisan migrate:rollback

# Rollback semua migration
php artisan migrate:reset

# Rollback dan jalankan ulang semua migration
php artisan migrate:refresh

# Rollback, jalankan ulang, dan jalankan seeder
php artisan migrate:refresh --seed
```

---

## Best Practices yang Diterapkan

✅ Menggunakan `foreignId()->constrained()` untuk foreign key
✅ Cascade delete untuk menjaga integritas data
✅ Soft delete pada tabel utama (reports)
✅ Enum untuk status yang terbatas
✅ Decimal untuk koordinat GPS dengan presisi tinggi
✅ Nullable pada kolom opsional
✅ Timestamps pada semua tabel
✅ Dokumentasi lengkap di setiap migration
✅ Urutan migration yang benar sesuai dependency
✅ Naming convention yang konsisten (snake_case)
✅ IP address field mendukung IPv4 dan IPv6

---

## Next Steps

Setelah migration selesai, Anda bisa:

1. **Membuat Model dengan Relasi**
2. **Membuat Seeder untuk Data Awal** (categories, districts)
3. **Membuat API Controller**
4. **Membuat API Routes**
5. **Implementasi Authentication (Sanctum)**
6. **Membuat Form Request Validation**
7. **Membuat API Resource untuk Response**
8. **Implementasi Upload Image**
9. **Implementasi Notification System**
10. **Membuat API Documentation**

---

**Dibuat untuk:** Aplikasi LaporPadang - Sistem Pelaporan Pengaduan Masyarakat Kota Padang
**Tech Stack:** Laravel 11, MySQL, Flutter
**Tanggal:** 13 Mei 2026
