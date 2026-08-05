@echo off
cls
echo =============================================
echo  TEST UPLOAD IMAGES - LAPORPADANG
echo =============================================
echo.

cd /d d:\latihan_flutter\padang\laporpadang

echo Perbaikan yang telah dilakukan:
echo.
echo [Backend Laravel]
echo   ✓ Accept base64 images dalam JSON request
echo   ✓ Support multipart/form-data file upload
echo   ✓ Save images ke storage/app/public/reports/
echo   ✓ Store paths dalam database (JSON array)
echo   ✓ Return full URLs dalam API response
echo.
echo [Frontend Flutter]
echo   ✓ Image picker enabled (camera + gallery)
echo   ✓ Convert images to base64
echo   ✓ Send dalam JSON request body
echo   ✓ Display images dengan ImageHelper
echo.
echo =============================================
echo  CHECK LOGS
echo =============================================
echo.
echo Logs location:
echo   storage\logs\laravel.log
echo.

if exist "storage\logs\laravel.log" (
    echo Last 30 lines of log:
    echo.
    powershell -Command "Get-Content 'storage\logs\laravel.log' -Tail 30"
) else (
    echo [INFO] No logs yet
)

echo.
echo =============================================
echo  CHECK UPLOADED FILES
echo =============================================
echo.

if exist "storage\app\public\reports" (
    echo Files in storage\app\public\reports:
    dir /b "storage\app\public\reports" 2>nul
    if %errorlevel% neq 0 (
        echo   (no files uploaded yet)
    ) else (
        echo.
        echo File details:
        dir "storage\app\public\reports"
    )
) else (
    echo [INFO] Reports directory not created yet
)

echo.
echo =============================================
echo  TEST FROM FLUTTER APP
echo =============================================
echo.
echo STEP 1: Build & install APK to phone
echo   Double-click: BUILD_DI_C.bat
echo   Then: RUN_KE_HP.bat
echo.
echo STEP 2: Open LaporPadang app
echo   - Tap "Buat Laporan"
echo   - Fill form
echo   - Tap "Tambah" to add photos
echo   - Choose from Camera or Gallery
echo   - Submit report
echo.
echo STEP 3: Check if images uploaded
echo   - Run this script again
echo   - Check files in storage\app\public\reports
echo   - Check database 'images' column
echo.
echo =============================================
echo  CHECK DATABASE
echo =============================================
echo.

php artisan tinker --execute="echo 'Last 5 reports with images:\n'; \$reports = App\Models\Report::latest()->take(5)->get(['id', 'title', 'images']); foreach(\$reports as \$r) { echo 'ID: ' . \$r->id . '\n'; echo 'Title: ' . \$r->title . '\n'; echo 'Images: ' . json_encode(\$r->images) . '\n'; echo '---\n'; }"

echo.
echo =============================================
echo  TROUBLESHOOTING
echo =============================================
echo.
echo Q: Images still empty [] in database?
echo A: Check logs (storage\logs\laravel.log) for errors
echo.
echo Q: Upload error from Flutter?
echo A: Check:
echo    1. Backend running: php artisan serve --host=0.0.0.0
echo    2. IP correct: 10.199.6.231:8000
echo    3. Token valid (re-login if needed)
echo.
echo Q: Images too large?
echo A: Default max 10MB per image
echo    Reduce quality in Flutter: imageQuality: 70
echo.
echo Q: File permission error?
echo A: Check storage folder permissions:
echo    chmod -R 775 storage (Linux/Mac)
echo    Or run as Administrator (Windows)
echo.
echo =============================================
echo  VIEW LOGS IN REAL-TIME
echo =============================================
echo.
echo To monitor logs while testing:
echo   powershell -Command "Get-Content storage\logs\laravel.log -Wait -Tail 20"
echo.
echo Press Ctrl+C to stop
echo.
pause
