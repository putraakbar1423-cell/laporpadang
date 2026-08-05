@echo off
cls
echo =============================================
echo  SETUP ADMIN LAPORPADANG
echo =============================================
echo.
echo Script ini akan:
echo 1. Register admin middleware
echo 2. Include admin routes
echo 3. Create admin user
echo 4. Start Laravel server
echo 5. Open admin dashboard
echo.
pause
echo.

cd /d d:\latihan_flutter\padang\laporpadang

echo [1/5] Checking files...
if not exist "app\Http\Middleware\Admin.php" (
    echo [ERROR] Admin middleware belum dibuat!
    echo File sudah ada di: app\Http\Middleware\Admin.php
    pause
    exit /b 1
)
echo [OK] Middleware found
echo.

echo [2/5] Checking routes...
if not exist "routes\admin.php" (
    echo [ERROR] Admin routes belum dibuat!
    echo File sudah ada di: routes\admin.php
    pause
    exit /b 1
)
echo [OK] Routes found
echo.

echo [3/5] Register middleware di Kernel.php...
echo Manual step required!
echo.
echo Edit: app\Http\Kernel.php
echo.
echo Tambahkan di $middlewareAliases array:
echo     'admin' =^> \App\Http\Middleware\Admin::class,
echo.
set /p done="Sudah ditambahkan? (y/n): "
if /i not "%done%"=="y" (
    echo Setup dibatalkan
    pause
    exit /b 1
)
echo.

echo [4/5] Include admin routes di web.php...
echo Manual step required!
echo.
echo Edit: routes\web.php
echo.
echo Tambahkan di paling bawah:
echo     require __DIR__.'/admin.php';
echo.
set /p done="Sudah ditambahkan? (y/n): "
if /i not "%done%"=="y" (
    echo Setup dibatalkan
    pause
    exit /b 1
)
echo.

echo [5/5] Create admin user...
echo.
echo Jalankan di terminal lain:
echo   php artisan tinker
echo.
echo Lalu copy-paste ini:
echo.
echo   User::create([
echo       'name' =^> 'Admin LaporPadang',
echo       'email' =^> 'admin@laporpadang.id',
echo       'password' =^> bcrypt('admin123'),
echo       'role' =^> 'admin',
echo       'phone' =^> '081234567890',
echo   ]);
echo.
echo   exit
echo.
set /p done="Sudah dibuat? (y/n): "
if /i not "%done%"=="y" (
    echo Setup dibatalkan
    pause
    exit /b 1
)
echo.

echo =============================================
echo  SETUP SELESAI!
echo =============================================
echo.
echo Next steps:
echo 1. Start server: JALANKAN_ADMIN.bat
echo 2. Open browser: http://localhost:8000/admin/dashboard
echo 3. Login dengan:
echo    Email: admin@laporpadang.id
echo    Password: admin123
echo.
pause
