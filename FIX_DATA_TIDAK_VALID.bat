@echo off
cls
echo =============================================
echo  FIX: DATA TIDAK VALID - LAPORPADANG
echo =============================================
echo.
echo MASALAH:
echo Saat kirim laporan dari Flutter app, muncul error
echo "data tidak valid" atau validation failed.
echo.
echo PENYEBAB:
echo Backend Laravel validasi kategori harus EXISTS di database
echo tapi table categories masih kosong!
echo.
echo SOLUSI:
echo Jalankan CategorySeeder untuk insert kategori ke database
echo.
pause
echo.

cd /d d:\latihan_flutter\padang\laporpadang

echo [1/3] Checking CategorySeeder...
if not exist "database\seeders\CategorySeeder.php" (
    echo [ERROR] CategorySeeder.php belum dibuat!
    echo File sudah ada di: database\seeders\CategorySeeder.php
    pause
    exit /b 1
)
echo [OK] CategorySeeder found
echo.

echo [2/3] Running CategorySeeder...
php artisan db:seed --class=CategorySeeder
echo.

if %errorlevel% equ 0 (
    echo [OK] Categories berhasil di-seed!
) else (
    echo [ERROR] Seeder gagal! Check error di atas.
    pause
    exit /b 1
)
echo.

echo [3/3] Verifying categories...
php artisan tinker --execute="echo Category::count() . ' categories found';"
echo.

echo =============================================
echo  FIX SELESAI!
echo =============================================
echo.
echo Kategori yang di-insert:
echo 1. Infrastruktur
echo 2. Kebersihan
echo 3. Lalu Lintas
echo 4. Penerangan
echo 5. Banjir
echo 6. Fasilitas Umum
echo 7. Lainnya
echo.
echo Sekarang coba kirim laporan lagi dari Flutter app!
echo.
echo =============================================
echo  TESTING
echo =============================================
echo.
echo 1. Buka Flutter app
echo 2. Buat laporan baru
echo 3. Isi form:
echo    - Judul: Test Laporan
echo    - Kategori: Infrastruktur (pilih dari dropdown)
echo    - Lokasi: Jl. Test No. 123
echo    - Deskripsi: Ini adalah test laporan
echo 4. Kirim
echo.
echo Expected: Laporan berhasil dikirim! ✓
echo.
pause
