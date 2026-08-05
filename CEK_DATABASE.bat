@echo off
cls
echo =============================================
echo  CEK DATABASE - LAPORPADANG
echo =============================================
echo.

cd /d d:\latihan_flutter\padang\laporpadang

echo Script ini akan menampilkan:
echo   1. Jumlah laporan di database
echo   2. Data laporan terbaru (last 5)
echo   3. Detail images field
echo   4. Files yang terupload
echo.
pause
echo.

echo =============================================
echo  1. JUMLAH LAPORAN
echo =============================================
echo.

php artisan tinker --execute="echo 'Total reports: ' . App\Models\Report::count() . '\n';"

if %errorlevel% neq 0 (
    echo [ERROR] Database connection failed!
    echo.
    echo Check:
    echo   1. MySQL/MariaDB running?
    echo   2. Database 'laporpadang' exists?
    echo   3. .env configured correctly?
    echo.
    pause
    exit /b 1
)

echo.
echo =============================================
echo  2. LAPORAN TERBARU (Last 5)
echo =============================================
echo.

php artisan tinker --execute="echo '\n'; \$reports = App\Models\Report::with('user', 'category')->latest()->take(5)->get(); if(\$reports->isEmpty()) { echo 'Belum ada laporan\n'; } else { foreach(\$reports as \$r) { echo '─────────────────────────────────────────\n'; echo 'ID       : ' . \$r->id . '\n'; echo 'Title    : ' . \$r->title . '\n'; echo 'Category : ' . (\$r->category ? \$r->category->name : 'N/A') . '\n'; echo 'User     : ' . (\$r->user ? \$r->user->name : 'N/A') . '\n'; echo 'Location : ' . \$r->address . '\n'; echo 'Status   : ' . \$r->status . '\n'; echo 'Images   : '; if(\$r->images && is_array(\$r->images)) { echo count(\$r->images) . ' file(s)\n'; foreach(\$r->images as \$img) { echo '           - ' . \$img . '\n'; } } else { echo '(no images)\n'; } echo 'Created  : ' . \$r->created_at->format('Y-m-d H:i:s') . '\n'; } }"

echo.
echo =============================================
echo  3. DETAIL IMAGES FIELD
echo =============================================
echo.

php artisan tinker --execute="echo '\n'; \$reports = App\Models\Report::whereNotNull('images')->latest()->take(3)->get(['id', 'title', 'images']); if(\$reports->isEmpty()) { echo 'Tidak ada laporan dengan images\n'; } else { foreach(\$reports as \$r) { echo '─────────────────────────────────────────\n'; echo 'Report ID: ' . \$r->id . '\n'; echo 'Title: ' . \$r->title . '\n'; echo 'Images Type: ' . gettype(\$r->images) . '\n'; echo 'Images Count: ' . (is_array(\$r->images) ? count(\$r->images) : 0) . '\n'; echo 'Images JSON: ' . json_encode(\$r->images, JSON_PRETTY_PRINT) . '\n'; } }"

echo.
echo =============================================
echo  4. FILES DI STORAGE
echo =============================================
echo.

if exist "storage\app\public\reports" (
    echo Directory: storage\app\public\reports
    echo.
    
    dir "storage\app\public\reports" | find /c ".jpg" > nul 2>&1
    if %errorlevel% equ 0 (
        echo Files found:
        dir /b /o-d "storage\app\public\reports\*.jpg" 2>nul
        dir /b /o-d "storage\app\public\reports\*.png" 2>nul
        dir /b /o-d "storage\app\public\reports\*.jpeg" 2>nul
        echo.
        echo File details:
        dir "storage\app\public\reports" | findstr /i ".jpg .png .jpeg"
    ) else (
        echo [INFO] No image files found
    )
) else (
    echo [INFO] Directory 'storage\app\public\reports' belum ada
    echo (akan dibuat otomatis saat pertama upload)
)

echo.
echo =============================================
echo  5. CEK STORAGE LINK
echo =============================================
echo.

if exist "public\storage" (
    echo [OK] Storage link exists: public\storage
    echo.
    echo Symlink details:
    dir public\storage | findstr /i "<"
) else (
    echo [WARNING] Storage link tidak ada!
    echo.
    echo Fix dengan run:
    echo   php artisan storage:link
    echo.
)

echo.
echo =============================================
echo  6. STATISTIK DATABASE
echo =============================================
echo.

php artisan tinker --execute="echo '\n=== STATISTICS ===\n'; echo 'Total Reports: ' . App\Models\Report::count() . '\n'; echo 'Reports with Images: ' . App\Models\Report::whereNotNull('images')->whereRaw('JSON_LENGTH(images) > 0')->count() . '\n'; echo 'Reports without Images: ' . App\Models\Report::whereNull('images')->orWhereRaw('JSON_LENGTH(images) = 0')->count() . '\n'; echo '\nBy Status:\n'; foreach(['pending', 'process', 'done', 'rejected'] as \$s) { echo '  ' . ucfirst(\$s) . ': ' . App\Models\Report::where('status', \$s)->count() . '\n'; } echo '\nBy Category:\n'; \$cats = App\Models\Category::withCount('reports')->get(); foreach(\$cats as \$c) { echo '  ' . \$c->name . ': ' . \$c->reports_count . '\n'; }"

echo.
echo =============================================
echo  7. LOGS TERBARU
echo =============================================
echo.

if exist "storage\logs\laravel.log" (
    echo Last 20 lines from laravel.log:
    echo.
    powershell -Command "Get-Content 'storage\logs\laravel.log' -Tail 20 | Where-Object { $_ -match 'Report|Image|image' }"
) else (
    echo [INFO] No logs yet
)

echo.
echo =============================================
echo  SUMMARY
echo =============================================
echo.

php artisan tinker --execute="\$total = App\Models\Report::count(); \$withImages = App\Models\Report::whereNotNull('images')->whereRaw('JSON_LENGTH(images) > 0')->count(); \$noImages = \$total - \$withImages; echo '\n┌────────────────────────────────────────┐\n'; echo '│  DATABASE STATUS                       │\n'; echo '├────────────────────────────────────────┤\n'; echo '│  Total Reports: ' . str_pad(\$total, 22) . '│\n'; echo '│  With Images:   ' . str_pad(\$withImages, 22) . '│\n'; echo '│  No Images:     ' . str_pad(\$noImages, 22) . '│\n'; echo '└────────────────────────────────────────┘\n';"

echo.
echo =============================================
echo  NEXT STEPS
echo =============================================
echo.
echo Jika data belum ada:
echo   1. Test dari Flutter app:
echo      - BUILD_DI_C.bat
echo      - RUN_KE_HP.bat
echo      - Buat laporan dengan foto
echo.
echo   2. Test dari Postman:
echo      - POST http://10.199.6.231:8000/api/v1/reports
echo      - Body: JSON dengan images base64
echo.
echo Jika images masih kosong []:
echo   1. Check logs: storage\logs\laravel.log
echo   2. Verify base64 format correct
echo   3. Check storage permissions
echo.
echo =============================================
echo.
pause
