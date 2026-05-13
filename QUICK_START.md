# ⚡ Quick Start Guide - LaporPadang Database

Panduan cepat untuk menjalankan migration database LaporPadang dalam 5 menit!

---

## 🚀 Langkah 1: Konfigurasi Database (1 menit)

Edit file `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laporpadang
DB_USERNAME=root
DB_PASSWORD=
```

---

## 🗄️ Langkah 2: Buat Database (30 detik)

```bash
# Masuk ke MySQL
mysql -u root -p

# Buat database
CREATE DATABASE laporpadang CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# Keluar
EXIT;
```

---

## ⚙️ Langkah 3: Jalankan Migration (1 menit)

```bash
php artisan migrate
```

**Output yang diharapkan:**

```
Migrating: 2026_05_13_105405_create_users_table
Migrated:  2026_05_13_105405_create_users_table (XX.XXms)
Migrating: 2026_05_13_105407_create_categories_table
Migrated:  2026_05_13_105407_create_categories_table (XX.XXms)
Migrating: 2026_05_13_105421_create_districts_table
Migrated:  2026_05_13_105421_create_districts_table (XX.XXms)
Migrating: 2026_05_13_105413_create_reports_table
Migrated:  2026_05_13_105413_create_reports_table (XX.XXms)
Migrating: 2026_05_13_105410_create_comments_table
Migrated:  2026_05_13_105410_create_comments_table (XX.XXms)
Migrating: 2026_05_13_105423_create_report_images_table
Migrated:  2026_05_13_105423_create_report_images_table (XX.XXms)
Migrating: 2026_05_13_105427_create_activity_logs_table
Migrated:  2026_05_13_105427_create_activity_logs_table (XX.XXms)
Migrating: 2026_05_13_105431_create_notifications_table
Migrated:  2026_05_13_105431_create_notifications_table (XX.XXms)
```

---

## 🌱 Langkah 4: Jalankan Seeder (1 menit)

```bash
# Seed categories (8 kategori)
php artisan db:seed --class=CategorySeeder

# Seed districts (11 kecamatan)
php artisan db:seed --class=DistrictSeeder
```

**Output yang diharapkan:**

```
Database seeding completed successfully.
```

---

## ✅ Langkah 5: Verifikasi (30 detik)

```bash
# Cek tabel yang dibuat
mysql -u root -p laporpadang -e "SHOW TABLES;"
```

**Output yang diharapkan:**

```
+------------------------+
| Tables_in_laporpadang  |
+------------------------+
| activity_logs          |
| categories             |
| comments               |
| districts              |
| migrations             |
| notifications          |
| report_images          |
| reports                |
| users                  |
+------------------------+
```

---

## 🎉 Selesai!

Database LaporPadang sudah siap digunakan dengan:

✅ 8 tabel database  
✅ Foreign key relations  
✅ 8 kategori laporan  
✅ 11 kecamatan Kota Padang  
✅ Soft delete enabled  
✅ Cascade delete configured  

---

## 🧪 Test Database (Opsional)

### Test 1: Insert User Admin

```sql
INSERT INTO users (name, email, role, password, created_at, updated_at) 
VALUES (
    'Admin LaporPadang',
    'admin@laporpadang.id',
    'admin',
    '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    NOW(),
    NOW()
);
```

### Test 2: Lihat Categories

```sql
SELECT * FROM categories;
```

### Test 3: Lihat Districts

```sql
SELECT * FROM districts;
```

---

## 📚 Dokumentasi Lengkap

Untuk informasi lebih detail, baca:

- **SUMMARY.md** - Ringkasan lengkap semua yang dibuat
- **DATABASE_STRUCTURE.md** - Struktur database dan ERD
- **MIGRATION_GUIDE.md** - Panduan lengkap migration
- **ERD_VISUAL.txt** - ERD visual dengan ASCII art
- **USEFUL_QUERIES.sql** - 100+ query SQL siap pakai

---

## 🔧 Troubleshooting

### Error: Connection Refused

**Solusi:**
1. Pastikan MySQL sudah running
2. Cek username dan password di `.env`
3. Cek port MySQL (default: 3306)

### Error: Database Not Found

**Solusi:**
```bash
mysql -u root -p
CREATE DATABASE laporpadang;
EXIT;
```

### Error: Foreign Key Constraint

**Solusi:**
```bash
# Rollback dan jalankan ulang
php artisan migrate:rollback
php artisan migrate
```

---

## 🎯 Next Steps

Setelah database siap, lanjutkan dengan:

1. **Buat Models** dengan relasi Eloquent
2. **Setup Laravel Sanctum** untuk API authentication
3. **Buat API Controllers** untuk CRUD operations
4. **Buat Form Requests** untuk validation
5. **Buat API Resources** untuk response formatting
6. **Setup Routes API** di `routes/api.php`
7. **Test API** dengan Postman

---

## 💡 Quick Commands

```bash
# Rollback migration terakhir
php artisan migrate:rollback

# Rollback semua migration
php artisan migrate:reset

# Rollback dan jalankan ulang
php artisan migrate:refresh

# Rollback, jalankan ulang, dan seed
php artisan migrate:refresh --seed

# Lihat status migration
php artisan migrate:status

# Lihat informasi database
php artisan db:show

# Lihat informasi tabel
php artisan db:table users
```

---

**Total Waktu Setup: ~5 menit** ⚡

**Status: ✅ READY FOR DEVELOPMENT**

---

**Happy Coding! 🚀**
