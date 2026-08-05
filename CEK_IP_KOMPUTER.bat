@echo off
cls
echo =============================================
echo  CEK IP KOMPUTER - LAPORPADANG
echo =============================================
echo.
echo Mendeteksi IP address komputer Anda...
echo.

ipconfig | findstr /i "IPv4"

echo.
echo =============================================
echo Catat IP address di atas (biasanya 192.168.x.x atau 10.x.x.x)
echo.
echo IP ini akan digunakan untuk:
echo 1. Backend Laravel server
echo 2. Flutter app di HP untuk connect ke backend
echo 3. Admin panel di browser
echo.
echo =============================================
pause
