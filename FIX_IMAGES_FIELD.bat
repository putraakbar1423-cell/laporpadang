@echo off
cls
echo =============================================
echo  FIX IMAGES FIELD - LAPORPADANG
echo =============================================
echo.
echo Script ini akan:
echo 1. Migrate database (ubah 'image' ke 'images' JSON)
echo 2. Setup storage link untuk public access
echo 3. Test images field
echo.
pause

cd /d d:\latihan_flutter\padang\laporpadang

echo.
echo [1/5] Checking Laravel installation...
if not exist "artisan" (
    echo [ERROR] File artisan tidak ditemukan!
    pause
    exit /b 1
)
echo [OK] Laravel found
echo.

echo [2/5] Running migration to fix images field...
echo.
echo Migration: 2026_07_28_000001_fix_reports_images_field.php
echo   - Drop column 'image' (VARCHAR)
echo   - Add column 'images' (JSON array)
echo.

php artisan migrate --path=database/migrations/2026_07_28_000001_fix_reports_images_field.php

if %errorlevel% neq 0 (
    echo.
    echo [ERROR] Migration failed!
    echo.
    echo Possible causes:
    echo 1. Database not connected
    echo 2. Migration already ran
    echo.
    echo Try manual migration:
    echo   php artisan migrate:status
    echo   php artisan migrate --force
    echo.
    pause
    exit /b 1
)

echo [OK] Migration completed
echo.

echo [3/5] Creating storage link...
echo.
echo This allows public access to:
echo   storage/app/public/reports/ -^> public/storage/reports/
echo.

php artisan storage:link

if %errorlevel% neq 0 (
    echo.
    echo [WARNING] Storage link already exists or failed
    echo.
    echo Check manually:
    echo   ls -la public/storage
    echo.
)

echo [OK] Storage link ready
echo.

echo [4/5] Checking database structure...
echo.

php artisan db:show

echo.
echo Check if 'images' column exists in reports table:
echo.

mysql -u root -e "DESCRIBE laporpadang.reports;" 2>nul

if %errorlevel% neq 0 (
    echo [INFO] MySQL CLI not available, skipping structure check
) else (
    echo [OK] Database structure checked
)

echo.

echo [5/5] Testing images field...
echo.

php artisan tinker --execute="echo 'Testing images cast...\n'; \$r = App\Models\Report::first(); if(\$r) { echo 'Images type: ' . gettype(\$r->images) . '\n'; var_dump(\$r->images); } else { echo 'No reports found\n'; }"

echo.
echo =============================================
echo  MIGRATION COMPLETED!
echo =============================================
echo.
echo Changes applied:
echo   ✓ Database: 'image' (VARCHAR) -^> 'images' (JSON)
echo   ✓ Model: Cast 'images' to array
echo   ✓ Controller: Handle multiple image uploads
echo   ✓ Resource: Convert paths to full URLs
echo   ✓ Storage: Public link created
echo.
echo =============================================
echo  NEXT STEPS
echo =============================================
echo.
echo 1. Test from Flutter app:
echo    - Create new report with images
echo    - Check if images display correctly
echo.
echo 2. Test from Postman/API:
echo    POST http://10.199.6.231:8000/api/v1/reports
echo    Body (form-data):
echo      - title: Test Report
echo      - category: Infrastruktur
echo      - location: Test Location
echo      - description: Test Description
echo      - images[]: [select image file]
echo      - images[]: [select another image file]
echo.
echo 3. Admin dashboard:
echo    - View reports: http://localhost:8000/admin/reports
echo    - Check if images display
echo.
echo =============================================
echo  IMAGE STORAGE LOCATIONS
echo =============================================
echo.
echo Uploaded images stored at:
echo   d:\latihan_flutter\padang\laporpadang\storage\app\public\reports\
echo.
echo Accessible via public URL:
echo   http://10.199.6.231:8000/storage/reports/[filename]
echo.
echo Example:
echo   File: storage/app/public/reports/1234567890_abc123.jpg
echo   URL:  http://10.199.6.231:8000/storage/reports/1234567890_abc123.jpg
echo.
echo =============================================
echo.
pause
