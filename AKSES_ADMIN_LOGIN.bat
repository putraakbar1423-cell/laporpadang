@echo off
cls
echo =============================================
echo  AKSES ADMIN DASHBOARD - DENGAN LOGIN
echo  LaporPadang
echo =============================================
echo.

cd /d d:\latihan_flutter\padang\laporpadang

echo CARA AKSES ADMIN DASHBOARD:
echo.
echo Ada 2 cara untuk akses admin:
echo   1. Login via Web Browser (RECOMMENDED)
echo   2. Login via Flutter App
echo.
echo =============================================
echo  METODE 1: LOGIN VIA WEB BROWSER
echo =============================================
echo.
echo DARI PC:
echo   1. Buka browser: http://localhost:8000/admin/login
echo   2. Login:
echo      Email: admin@laporpadang.id
echo      Password: admin123
echo   3. Redirect ke dashboard ✓
echo.
echo DARI HP:
echo   1. Buka Chrome: http://10.199.6.231:8000/admin/login
echo   2. Login dengan credentials yang sama
echo   3. Redirect ke dashboard ✓
echo.
echo =============================================
echo  PREREQUISITES
echo =============================================
echo.
echo [1/4] Checking if admin user exists...
php artisan tinker --execute="echo 'Admin users: ' . App\Models\User::where('role', 'admin')->count() . '\n';"

if %errorlevel% equ 0 (
    echo [OK] Database connected
) else (
    echo [ERROR] Database connection failed!
    pause
    exit /b 1
)

echo.
echo [2/4] Checking if Laravel server running...
curl -s http://localhost:8000 >nul 2>&1

if %errorlevel% equ 0 (
    echo [OK] Laravel server is running
) else (
    echo [WARNING] Laravel server not running!
    echo.
    echo Starting Laravel server...
    start cmd /k "php artisan serve --host=0.0.0.0 --port=8000"
    timeout /t 3 >nul
)

echo.
echo [3/4] Checking if firewall open...
netsh advfirewall firewall show rule name=all | findstr "8000" >nul 2>&1

if %errorlevel% equ 0 (
    echo [OK] Port 8000 firewall rule exists
) else (
    echo [WARNING] Port 8000 not opened in firewall!
    echo.
    echo Run: BUKA_FIREWALL.bat (as Administrator)
    echo.
)

echo.
echo [4/4] Getting IP address...
echo.
echo Your IP addresses:
ipconfig | findstr /i "IPv4"

echo.
echo =============================================
echo  READY TO ACCESS ADMIN
echo =============================================
echo.
echo DARI PC (Localhost):
echo   URL: http://localhost:8000/admin/login
echo.
echo DARI HP (Network):
echo   URL: http://10.199.6.231:8000/admin/login
echo   (ganti IP sesuai output di atas)
echo.
echo LOGIN CREDENTIALS:
echo   Email: admin@laporpadang.id
echo   Password: admin123
echo.
echo =============================================
echo.

set /p open_browser="Buka browser sekarang? (y/n): "

if /i "%open_browser%"=="y" (
    echo.
    echo Opening admin login page...
    start http://localhost:8000/admin/login
    echo.
    echo Halaman login dibuka!
    echo Gunakan credentials di atas untuk login.
    echo.
)

echo =============================================
echo  TROUBLESHOOTING
echo =============================================
echo.
echo Q: "No admin users found"?
echo A: Buat admin user:
echo    php artisan tinker
echo    User::create([
echo        'name' =^> 'Admin',
echo        'email' =^> 'admin@laporpadang.id',
echo        'password' =^> Hash::make('admin123'),
echo        'role' =^> 'admin',
echo        'phone' =^> '081234567890'
echo    ]);
echo.
echo Q: "Email atau password salah"?
echo A: Check credentials atau reset password
echo.
echo Q: "Tidak terautentikasi"?
echo A: Anda belum login! Akses /admin/login dulu
echo.
echo Q: "404 Not Found" di /admin/login?
echo A: Routes belum dimuat. Run:
echo    php artisan route:list ^| findstr admin
echo    php artisan config:clear
echo.
echo Q: Redirect loop?
echo A: Clear session:
echo    php artisan session:flush
echo    Clear browser cookies
echo.
echo =============================================
echo  FILE LOCATIONS
echo =============================================
echo.
echo Login page:
echo   resources\views\admin\login.blade.php
echo.
echo Routes:
echo   routes\web.php (admin login routes)
echo   routes\admin.php (admin dashboard routes)
echo.
echo Middleware:
echo   app\Http\Middleware\Admin.php
echo.
echo =============================================
echo  NEXT STEPS
echo =============================================
echo.
echo 1. Pastikan server running
echo 2. Buka: http://localhost:8000/admin/login
echo 3. Login dengan admin credentials
echo 4. Test dari HP: http://IP:8000/admin/login
echo 5. Enjoy admin dashboard! 🎉
echo.
echo =============================================
echo.
pause
