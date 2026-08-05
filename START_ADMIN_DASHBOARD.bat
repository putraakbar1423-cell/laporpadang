@echo off
cls
echo =============================================
echo  START ADMIN DASHBOARD - LAPORPADANG
echo =============================================
echo.

cd /d d:\latihan_flutter\padang\laporpadang

echo [1/5] Detecting IP addresses...
echo.

set "LOCAL_IP=Not Found"
for /f "tokens=2 delims=:" %%a in ('ipconfig ^| findstr /i "IPv4" ^| findstr /v "127.0.0.1"') do (
    set "LOCAL_IP=%%a"
    set LOCAL_IP=!LOCAL_IP: =!
    goto :ip_found
)

:ip_found
echo =============================================
echo  NETWORK INFORMATION
echo =============================================
echo.
echo Local Access (dari PC ini):
echo   http://localhost:8000
echo   http://127.0.0.1:8000
echo.
echo Network Access (dari HP/device lain):
echo   http://%LOCAL_IP%:8000
echo.
echo =============================================
echo  ADMIN DASHBOARD URLS
echo =============================================
echo.
echo Local:
echo   http://localhost:8000/admin/dashboard
echo.
echo Network:
echo   http://%LOCAL_IP%:8000/admin/dashboard
echo.
echo =============================================
echo  LOGIN CREDENTIALS
echo =============================================
echo.
echo Email:    admin@laporpadang.id
echo Password: admin123
echo.
echo (Jika belum ada, jalankan: SETUP_ADMIN.bat)
echo.
echo =============================================
echo.
pause

echo [2/5] Checking Laravel...
if not exist "artisan" (
    echo [ERROR] File artisan tidak ditemukan!
    echo Pastikan Anda berada di folder laporpadang
    pause
    exit /b 1
)
echo [OK] Laravel found
echo.

echo [3/5] Checking database connection...
php artisan db:show 2>nul
if %errorlevel% neq 0 (
    echo [WARNING] Database connection gagal!
    echo Check .env file:
    echo   DB_CONNECTION=mysql
    echo   DB_HOST=127.0.0.1
    echo   DB_PORT=3306
    echo   DB_DATABASE=laporpadang
    echo   DB_USERNAME=root
    echo   DB_PASSWORD=
    echo.
    set /p continue="Lanjutkan? (y/n): "
    if /i not "!continue!"=="y" exit /b 1
)
echo.

echo [4/5] Checking admin routes...
php artisan route:list --name=admin.dashboard >nul 2>&1
if %errorlevel% neq 0 (
    echo [WARNING] Admin routes belum terdaftar!
    echo.
    echo Run setup dulu: SETUP_ADMIN.bat
    echo.
    set /p continue="Lanjutkan? (y/n): "
    if /i not "!continue!"=="y" exit /b 1
)
echo [OK] Admin routes registered
echo.

echo [5/5] Starting Laravel server...
echo.
echo =============================================
echo  SERVER STARTING
echo =============================================
echo.
echo Server akan running di:
echo   - http://localhost:8000
echo   - http://%LOCAL_IP%:8000
echo.
echo Buka browser dan akses:
echo   http://localhost:8000/admin/dashboard
echo.
echo Press Ctrl+C to stop server
echo.
echo =============================================
echo.

php artisan serve --host=0.0.0.0 --port=8000
