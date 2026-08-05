@echo off
cls
echo =============================================
echo  TEST IMAGES API - LAPORPADANG
echo =============================================
echo.

cd /d d:\latihan_flutter\padang\laporpadang

echo This script helps you test the images API
echo.
echo =============================================
echo  MIGRATION STATUS
echo =============================================
echo.

php artisan migrate:status | findstr "fix_reports_images_field"

if %errorlevel% neq 0 (
    echo [WARNING] Migration not found in status
    echo Run: FIX_IMAGES_FIELD.bat
    echo.
)

echo.
echo =============================================
echo  STORAGE LINK STATUS
echo =============================================
echo.

if exist "public\storage" (
    echo [OK] Storage link exists: public\storage
) else (
    echo [ERROR] Storage link not found!
    echo Run: php artisan storage:link
)

echo.
echo =============================================
echo  TEST API WITH POSTMAN
echo =============================================
echo.
echo STEP 1: Login to get token
echo   POST http://10.199.6.231:8000/api/v1/auth/login
echo   Body:
echo     email: user@example.com
echo     password: password123
echo.
echo STEP 2: Create report with images
echo   POST http://10.199.6.231:8000/api/v1/reports
echo   Headers:
echo     Authorization: Bearer {token}
echo     Content-Type: multipart/form-data
echo   Body (form-data):
echo     title: Test Laporan Gambar
echo     category: Infrastruktur
echo     location: Jl. Test, Padang
echo     description: Testing upload multiple images
echo     latitude: -0.947083
echo     longitude: 100.417419
echo     images[]: [select image file 1]
echo     images[]: [select image file 2]
echo     images[]: [select image file 3]
echo.
echo STEP 3: Get reports list
echo   GET http://10.199.6.231:8000/api/v1/reports
echo   Headers:
echo     Authorization: Bearer {token}
echo.
echo   Check response:
echo     "images": [
echo       "http://10.199.6.231:8000/storage/reports/123.jpg",
echo       "http://10.199.6.231:8000/storage/reports/456.jpg"
echo     ]
echo.
echo =============================================
echo  TEST WITH CURL
echo =============================================
echo.
echo Get token first:
echo.
echo curl -X POST http://10.199.6.231:8000/api/v1/auth/login ^
echo   -H "Content-Type: application/json" ^
echo   -d "{\"email\":\"user@example.com\",\"password\":\"password123\"}"
echo.
echo.
echo Upload with images (replace {token}):
echo.
echo curl -X POST http://10.199.6.231:8000/api/v1/reports ^
echo   -H "Authorization: Bearer {token}" ^
echo   -F "title=Test Laporan" ^
echo   -F "category=Infrastruktur" ^
echo   -F "location=Jl. Test" ^
echo   -F "description=Test Description" ^
echo   -F "images[]=@C:\path\to\image1.jpg" ^
echo   -F "images[]=@C:\path\to\image2.jpg"
echo.
echo =============================================
echo  CHECK UPLOADED FILES
echo =============================================
echo.
echo Uploaded files location:
echo   d:\latihan_flutter\padang\laporpadang\storage\app\public\reports\
echo.

if exist "storage\app\public\reports" (
    echo Files in storage:
    dir /b "storage\app\public\reports" 2>nul
    if %errorlevel% neq 0 (
        echo   (no files yet)
    )
) else (
    echo [INFO] Reports directory not created yet
    echo Will be created automatically on first upload
)

echo.
echo =============================================
echo  TROUBLESHOOTING
echo =============================================
echo.
echo Q: Upload returns "data tidak valid"
echo A: Check categories table - must have data
echo    Run: FIX_DATA_TIDAK_VALID.bat
echo.
echo Q: Images array empty in response
echo A: Check Model cast: 'images' =^> 'array'
echo    File: app\Models\Report.php
echo.
echo Q: 404 on image URL
echo A: Check storage link exists
echo    Run: php artisan storage:link
echo.
echo Q: Migration error
echo A: Already ran. Check with:
echo    php artisan migrate:status
echo.
echo =============================================
echo  DOCUMENTATION
echo =============================================
echo.
echo Full guide: PERBAIKAN_IMAGES.md
echo.
pause
