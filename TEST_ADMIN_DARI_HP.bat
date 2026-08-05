@echo off
cls
echo =============================================
echo  TEST ADMIN DASHBOARD DARI HP
echo  LaporPadang
echo =============================================
echo.

cd /d d:\latihan_flutter\padang\laporpadang

echo TROUBLESHOOTING GUIDE
echo Jika Admin Dashboard tidak bisa diakses dari HP
echo.
echo =============================================
echo  STEP 1: CEK IP KOMPUTER
echo =============================================
echo.
echo IP Address komputer Anda:
ipconfig | findstr /i "IPv4"
echo.
echo Catat IP yang sesuai dengan network HP Anda
echo Biasanya: 10.199.6.231 (sesuai WiFi/network)
echo.
pause

echo.
echo =============================================
echo  STEP 2: CEK FIREWALL
echo =============================================
echo.
echo Checking firewall rules...
netsh advfirewall firewall show rule name=all | findstr /i "8000" >nul 2>&1

if %errorlevel% equ 0 (
    echo [OK] Port 8000 rules exist
    echo.
    netsh advfirewall firewall show rule name=all | findstr /i "8000"
) else (
    echo [WARNING] Port 8000 belum dibuka di firewall!
    echo.
    echo Solusi:
    echo 1. Jalankan: BUKA_FIREWALL.bat (as Administrator)
    echo 2. Atau manual: Control Panel -^> Firewall -^> Allow port 8000
    echo.
)
pause

echo.
echo =============================================
echo  STEP 3: CEK LARAVEL SERVER
echo =============================================
echo.
echo Apakah Laravel server sudah running?
echo.
set /p server_running="Laravel server running? (y/n): "

if /i "%server_running%"=="n" (
    echo.
    echo Starting Laravel server...
    echo.
    echo Server akan berjalan di:
    echo   - Local: http://localhost:8000
    echo   - Network: http://10.199.6.231:8000
    echo.
    echo Press Ctrl+C to stop
    echo.
    start cmd /k "php artisan serve --host=0.0.0.0 --port=8000"
    timeout /t 3 >nul
)

echo.
echo =============================================
echo  STEP 4: TEST DARI KOMPUTER DULU
echo =============================================
echo.
echo Test akses dari komputer:
echo.

curl -I http://localhost:8000/admin/dashboard 2>nul | findstr "200 OK" >nul

if %errorlevel% equ 0 (
    echo [OK] Admin dashboard accessible from localhost
) else (
    echo [WARNING] Admin dashboard tidak bisa diakses dari localhost
    echo Check:
    echo   1. Laravel server running?
    echo   2. Routes configured? (routes/admin.php)
    echo   3. Check: php artisan route:list
)

echo.
echo Opening in browser...
start http://localhost:8000/admin/dashboard
echo.
pause

echo.
echo =============================================
echo  STEP 5: TEST DARI HP
echo =============================================
echo.
echo Sekarang test dari HP:
echo.
echo 1. Pastikan HP dan PC terhubung ke WiFi/network yang sama
echo.
echo 2. Buka Chrome di HP
echo.
echo 3. Ketik URL (ganti IP sesuai Step 1):
echo    http://10.199.6.231:8000/admin/dashboard
echo.
echo 4. Jika muncul halaman login:
echo    Email: admin@laporpadang.id
echo    Password: admin123
echo.
echo 5. Jika TIDAK muncul, cek:
echo    - Firewall Windows (Run BUKA_FIREWALL.bat as Admin)
echo    - IP address benar?
echo    - WiFi sama?
echo    - Antivirus blocking?
echo.
pause

echo.
echo =============================================
echo  TROUBLESHOOTING CHECKLIST
echo =============================================
echo.
echo [ ] Laravel server running (php artisan serve --host=0.0.0.0)
echo [ ] Port 8000 dibuka di firewall
echo [ ] IP address benar
echo [ ] HP dan PC di network yang sama
echo [ ] Bisa akses dari localhost:8000
echo [ ] URL benar: http://IP:8000/admin/dashboard
echo [ ] Antivirus tidak blocking
echo.
echo =============================================
echo  COMMON ISSUES
echo =============================================
echo.
echo Q: "This site can't be reached"
echo A: 1. Check firewall (BUKA_FIREWALL.bat)
echo    2. Check IP address correct
echo    3. Check network same
echo.
echo Q: "Connection refused"
echo A: Laravel server not running or wrong IP
echo.
echo Q: "ERR_CONNECTION_TIMED_OUT"
echo A: Firewall blocking atau antivirus
echo.
echo Q: "404 Not Found"
echo A: URL salah, tambahkan /admin/dashboard
echo.
echo Q: Blank page
echo A: 1. Check browser console (F12)
echo    2. Check Laravel logs: storage/logs/laravel.log
echo.
echo =============================================
echo  ALTERNATIVE ACCESS METHODS
echo =============================================
echo.
echo Jika tetap tidak bisa:
echo.
echo METHOD 1: Use ngrok (tunnel)
echo   1. Download ngrok: https://ngrok.com
echo   2. Run: ngrok http 8000
echo   3. Use ngrok URL from HP
echo.
echo METHOD 2: Use same-network with IP
echo   1. Check router settings
echo   2. Ensure no AP isolation
echo   3. Try different WiFi network
echo.
echo METHOD 3: USB Tethering
echo   1. Connect HP to PC via USB
echo   2. Enable USB tethering on HP
echo   3. Check new IP: ipconfig
echo   4. Use that IP
echo.
echo =============================================
echo.
echo Need more help?
echo   - Check Laravel logs: storage/logs/laravel.log
echo   - Run: php artisan route:list
echo   - Test API: http://IP:8000/api/v1/categories
echo.
pause
