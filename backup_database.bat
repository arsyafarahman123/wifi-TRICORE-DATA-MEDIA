@echo off
title Backup Database Layanan WiFi dari Hosting InfinityFree
color 0A

echo ================================================================
echo   AUTO-BACKUP DATABASE LAYANAN WIFI TRICORE
echo   Mengunduh database terbaru dari hosting ke laptop Anda...
echo ================================================================
echo.

python -c "
import ftplib, os, datetime

FTP_HOST = 'ftpupload.net'
FTP_USER = 'if0_43041974'
FTP_PASS = 'b33wlGdHrhtRTF'
REMOTE_PATH = '/htdocs/database/database.sqlite'

backup_dir = os.path.join(os.path.dirname(os.path.abspath(__file__)), 'backups')
if not os.path.exists(backup_dir):
    os.makedirs(backup_dir)

now_str = datetime.datetime.now().strftime('%Y-%m-%d_%H-%M-%S')
local_backup = os.path.join(backup_dir, f'database_backup_{now_str}.sqlite')
latest_file = os.path.join(os.path.dirname(os.path.abspath(__file__)), 'database', 'database.sqlite')

print(f'[*] Menghubungkan ke hosting FTP ({FTP_USER})...')
ftp = ftplib.FTP(FTP_HOST, timeout=30)
ftp.login(FTP_USER, FTP_PASS)
ftp.set_pasv(True)

print(f'[*] Mengunduh database...')
with open(local_backup, 'wb') as f:
    ftp.retrbinary(f'RETR {REMOTE_PATH}', f.write)

with open(latest_file, 'wb') as f:
    ftp.retrbinary(f'RETR {REMOTE_PATH}', f.write)

ftp.quit()

size_kb = os.path.getsize(local_backup) / 1024
print(f'[✓] BERHASIL! Database tersimpan di: {local_backup} ({size_kb:.2f} KB)')
print(f'[✓] Salinan lokal terbaru juga diperbarui di: {latest_file}')
"

echo.
echo ================================================================
echo   BACKUP SELESAI! DATA ANDA AMAN TERSIMPAN DI LAPTOP.
echo ================================================================
pause
