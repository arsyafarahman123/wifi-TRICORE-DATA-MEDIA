@echo off
title Deploy ke InfinityFree - Layanan WiFi TriCore Data Media
color 0B

echo ================================================================
echo   DEPLOY LAYANAN WIFI TRICORE KE INFINITYFREE
echo   Akun Google: arsyafathiharahman27@gmail.com
echo ================================================================
echo.

echo ================================================================
echo   LANGKAH 1: BUAT AKUN DI INFINITYFREE
echo ================================================================
echo   1. Buka browser, login dengan: arsyafathiharahman27@gmail.com
echo   2. Masuk ke https://dash.infinityfree.com
echo   3. Klik "Create Account"
echo   4. Masukkan nama subdomain (contoh: wifi-tricore.infinityfreeapp.com)
echo.

echo ================================================================
echo   LANGKAH 2: BUAT DATABASE MYSQL
echo ================================================================
echo   1. Di dashboard akun InfinityFree, klik "MySQL Databases"
echo   2. Buat database baru (contoh: wifi)
echo   3. Catat detailnya:
echo      - MySQL Host     : sqlXXX.infinityfree.com
echo      - Database Name  : if0_XXXXXXXX_wifi
echo      - MySQL Username : if0_XXXXXXXX
echo      - MySQL Password : password akun vPanel Anda
echo.

echo ================================================================
echo   LANGKAH 3: UPDATE .env.production
echo ================================================================
echo   Buka dan edit file .env.production di folder ini, sesuaikan:
echo   - APP_URL
echo   - DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD
echo.

echo ================================================================
echo   PILIHAN CARA UPLOAD:
echo ================================================================
echo   [1] Jalankan upload otomatis via Python script (upload_infinityfree.py)
echo   [2] Buka folder untuk upload manual via FileZilla FTP
echo   [3] Build ulang asset (npm run build)
echo   [4] Keluar
echo ================================================================
echo.

set /p pilihan="Pilih menu [1/2/3/4]: "

if "%pilihan%"=="1" (
    echo.
    set /p userftp="Masukkan FTP Username (contoh if0_12345678): "
    set /p passftp="Masukkan FTP Password: "
    set IF_FTP_USER=%userftp%
    set IF_FTP_PASS=%passftp%
    python upload_infinityfree.py
    pause
    exit /b
)

if "%pilihan%"=="2" (
    explorer .
    echo Silakan buka FileZilla, hubungkan ke ftpupload.net dan upload isi folder ini ke /htdocs/
    pause
    exit /b
)

if "%pilihan%"=="3" (
    call npm run build
    pause
    exit /b
)

pause
