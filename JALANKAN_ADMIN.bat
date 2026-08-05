@echo off
cls
echo =============================================
echo  JALANKAN ADMIN LAPORPADANG
echo =============================================
echo.

cd /d d:\latihan_flutter\padang\laporpadang

echo Detecting IP address...
for /f "tokens=2 delims=:" %%a in ('ipconfig ^| findstr /i "IPv4" ^| findstr /v "127.0.0.1"') do (
    set IP=%%a
    goto :found
)

:found
set IP=%IP: =%
echo.
echo =============================================
echo  SERVER INFORMATION
echo =============================================
echo.
echo Local URL:    http://localhost:8000
echo Network URL:  http://%IP%:8000
echo.
echo Admin Panel:
echo   Local:      http://localhost:8000/admin/dashboard
echo   Network:    http://%IP%:8000/admin/dashboard
echo.
echo API Base URL: http://%IP%:8000/api/v1
echo.
echo Login Admin:
echo   Email:    admin@laporpadang.id
echo   Password: admin123
echo.
echo =============================================
echo.
echo Starting Laravel server...
echo Press Ctrl+C to stop
echo.

php artisan serve --host=0.0.0.0 --port=8000
