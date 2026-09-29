@echo off
title Auto-Backup Database ke GitHub - Layanan WiFi TriCore
color 0B

echo ================================================================
echo   AUTO-BACKUP DATABASE LAYANAN WIFI KE GITHUB
echo   Repository: github.com/arsyafarahman123/wifi-TRICORE-DATA-MEDIA
echo ================================================================
echo.

python -c "
import ftplib, os, datetime

FTP_HOST = 'ftpupload.net'
FTP_USER = 'if0_43041974'
FTP_PASS = 'b33wlGdHrhtRTF'
REMOTE_PATH = '/htdocs/database/database.sqlite'

base_dir = os.path.dirname(os.path.abspath(__file__))
backup_dir = os.path.join(base_dir, 'backups')
if not os.path.exists(backup_dir):
    os.makedirs(backup_dir)

now_str = datetime.datetime.now().strftime('%Y-%m-%d_%H-%M-%S')
local_backup = os.path.join(backup_dir, f'database_backup_{now_str}.sqlite')
latest_file = os.path.join(base_dir, 'database', 'database.sqlite')

print(f'[*] Mengunduh database terbaru dari hosting...')
ftp = ftplib.FTP(FTP_HOST, timeout=30)
ftp.login(FTP_USER, FTP_PASS)
ftp.set_pasv(True)

with open(local_backup, 'wb') as f:
    ftp.retrbinary(f'RETR {REMOTE_PATH}', f.write)

with open(latest_file, 'wb') as f:
    ftp.retrbinary(f'RETR {REMOTE_PATH}', f.write)

ftp.quit()

size_kb = os.path.getsize(latest_file) / 1024
print(f'[✓] Database berhasil diunduh ke laptop ({size_kb:.2f} KB)')
"

echo.
echo [*] Menyimpan dan mengunggah backup ke GitHub...
git add database/database.sqlite
git commit -m "chore(backup): Auto-backup database live %date% %time%"
git push origin main

echo.
echo ================================================================
echo   [BERHASIL] DATABASE SUDAH TERSIMPAN AMAN DI REPO GITHUB ANDA!
echo   Bahkan jika hosting gratis di-reset, data tetap 100%% aman.
echo ================================================================
pause
