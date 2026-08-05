@echo off
cls
echo =============================================
echo  BUKA FIREWALL UNTUK LARAVEL - LAPORPADANG
echo =============================================
echo.
echo Script ini akan membuka port 8000 di Windows Firewall
echo agar HP bisa akses Admin Dashboard Laravel
echo.
echo IMPORTANT: Run as Administrator!
echo (Klik kanan file ini -^> Run as Administrator)
echo.
pause

echo.
echo [1/3] Checking if running as Administrator...
net session >nul 2>&1
if %errorlevel% neq 0 (
    echo [ERROR] Tidak dijalankan sebagai Administrator!
    echo.
    echo Cara menjalankan:
    echo 1. Klik kanan file BUKA_FIREWALL.bat
    echo 2. Pilih "Run as administrator"
    echo.
    pause
    exit /b 1
)
echo [OK] Running as Administrator
echo.

echo [2/3] Membuka port 8000 untuk Laravel...
echo.

REM Hapus rule lama jika ada
netsh advfirewall firewall delete rule name="Laravel Dev Server 8000" >nul 2>&1

REM Tambah rule baru untuk Inbound (dari HP ke PC)
netsh advfirewall firewall add rule name="Laravel Dev Server 8000" dir=in action=allow protocol=TCP localport=8000

if %errorlevel% equ 0 (
    echo [OK] Firewall rule added successfully!
) else (
    echo [ERROR] Failed to add firewall rule
    pause
    exit /b 1
)

echo.
echo [3/3] Verifying firewall rule...
netsh advfirewall firewall show rule name="Laravel Dev Server 8000"

echo.
echo =============================================
echo  FIREWALL CONFIGURED!
echo =============================================
echo.
echo Port 8000 sudah dibuka untuk:
echo   - Protocol: TCP
echo   - Direction: Inbound (dari HP ke PC)
echo   - Action: Allow
echo.
echo Sekarang HP bisa akses:
echo   http://10.199.6.231:8000/admin/dashboard
echo.
echo =============================================
echo  NEXT STEPS
echo =============================================
echo.
echo 1. Pastikan Laravel server running:
echo    php artisan serve --host=0.0.0.0 --port=8000
echo.
echo 2. Buka Chrome di HP
echo.
echo 3. Ketik URL:
echo    http://10.199.6.231:8000/admin/dashboard
echo.
echo 4. Login:
echo    Email: admin@laporpadang.id
echo    Password: admin123
echo.
echo =============================================
echo.
pause
