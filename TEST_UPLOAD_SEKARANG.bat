@echo off
cls
echo =============================================
echo  TEST UPLOAD IMAGES - SEKARANG!
echo =============================================
echo.

cd /d d:\latihan_flutter\padang\laporpadang

echo STATUS SAAT INI:
echo   - Total reports: 10
echo   - With images: 0
echo   - Files uploaded: 0
echo.
echo PERBAIKAN SUDAH DILAKUKAN:
echo   ✓ Laravel accept base64 images
echo   ✓ Flutter convert to base64
echo   ✓ Save logic implemented
echo.
echo =============================================
echo  CARA TEST (PILIH SALAH SATU)
echo =============================================
echo.
echo OPTION 1: Test dari Flutter App (RECOMMENDED)
echo ─────────────────────────────────────────────
echo.
echo STEP 1: Build APK baru (dengan perbaikan)
echo   Double-click: BUILD_DI_C.bat
echo   (build di C: drive, lebih cepat)
echo.
echo STEP 2: Install ke HP
echo   Double-click: RUN_KE_HP.bat
echo.
echo STEP 3: Test upload
echo   1. Buka app LaporPadang
echo   2. Tap "Buat Laporan"
echo   3. Isi form:
echo      - Kategori: Infrastruktur
echo      - Judul: Test Upload Foto
echo      - Deskripsi: Testing upload multiple images
echo      - Lokasi: Jl. Test, Padang
echo   4. Tap "Tambah" foto
echo   5. Pilih 2-3 foto dari galeri atau camera
echo   6. Submit
echo.
echo STEP 4: Verifikasi
echo   Double-click: CEK_DATABASE.bat
echo   (akan tampil images yang terupload)
echo.
pause
echo.
echo =============================================
echo.
echo OPTION 2: Test dari Postman/API Client
echo ─────────────────────────────────────────────
echo.
echo STEP 1: Start Laravel server
echo   php artisan serve --host=0.0.0.0 --port=8000
echo.
echo STEP 2: Login untuk get token
echo   POST http://10.199.6.231:8000/api/v1/auth/login
echo   Body (JSON):
echo   {
echo     "email": "user@example.com",
echo     "password": "password123"
echo   }
echo.
echo STEP 3: Create report dengan images
echo   POST http://10.199.6.231:8000/api/v1/reports
echo   Headers:
echo     Authorization: Bearer YOUR_TOKEN
echo     Content-Type: application/json
echo   Body (JSON):
echo   {
echo     "title": "Test Upload Images",
echo     "category": "Infrastruktur",
echo     "location": "Jl. Test Padang",
echo     "description": "Testing upload images",
echo     "images": [
echo       "data:image/jpeg;base64,/9j/4AAQSkZJRg...",
echo       "data:image/jpeg;base64,/9j/4AAQSkZJRg..."
echo     ]
echo   }
echo.
echo   Note: Ganti dengan base64 image sebenarnya
echo   Tool: https://www.base64-image.de/
echo.
pause
echo.
echo =============================================
echo  MONITOR LOGS REAL-TIME
echo =============================================
echo.
echo Untuk monitor upload process:
echo   powershell -Command "Get-Content storage\logs\laravel.log -Wait -Tail 20"
echo.
echo Look for messages:
echo   - "Processing images array"
echo   - "Base64 image saved"
echo   - "Final image paths"
echo.
echo Press Ctrl+C to stop monitoring
echo.
pause
echo.
echo =============================================
echo  START LARAVEL SERVER
echo =============================================
echo.

set /p start_server="Start Laravel server sekarang? (y/n): "

if /i "%start_server%"=="y" (
    echo.
    echo Starting server...
    echo Server akan running di:
    echo   - http://localhost:8000
    echo   - http://10.199.6.231:8000
    echo.
    echo Press Ctrl+C to stop
    echo.
    php artisan serve --host=0.0.0.0 --port=8000
) else (
    echo.
    echo OK, start server manual jika perlu:
    echo   php artisan serve --host=0.0.0.0 --port=8000
    echo.
)

echo.
echo =============================================
echo  AFTER UPLOAD - CHECK RESULTS
echo =============================================
echo.
echo Run script ini untuk cek hasil:
echo   CEK_DATABASE.bat
echo.
echo Atau run manual:
echo   php check_reports.php
echo.
pause
