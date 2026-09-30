import os
import sys
import ftplib
import time

FTP_HOST = 'ftpupload.net'
FTP_USER = 'if0_43041974'
FTP_PASS = 'b33wlGdHrhtRTF'
REMOTE_ROOT = '/htdocs'

def connect_ftp():
    print(f"[*] Menghubungkan ke FTP ({FTP_HOST})...")
    ftp = ftplib.FTP(FTP_HOST, timeout=60)
    ftp.login(FTP_USER, FTP_PASS)
    ftp.set_pasv(True)
    print(f"[OK] Berhasil login FTP sebagai {FTP_USER}")
    return ftp

def ensure_remote_dir(ftp, remote_dir):
    parts = remote_dir.strip('/').split('/')
    current = ''
    for part in parts:
        current += '/' + part
        try:
            ftp.cwd(current)
        except ftplib.error_perm:
            try:
                ftp.mkd(current)
                print(f"[+] Membuat folder: {current}")
            except Exception:
                pass

def upload_file(ftp, local_path, remote_path, max_retries=3):
    for attempt in range(max_retries):
        try:
            with open(local_path, 'rb') as f:
                ftp.storbinary(f'STOR {remote_path}', f)
            print(f"[OK] Terupload: {remote_path}")
            return True
        except Exception as e:
            print(f"[!] Gagal ({attempt+1}/{max_retries}) {remote_path}: {e}")
            time.sleep(1)
    return False

def main():
    base_dir = os.path.dirname(os.path.abspath(__file__))
    ftp = connect_ftp()

    files_to_upload = [
        'routes/web.php',
        'app/Http/Controllers/ChatbotController.php',
        'resources/views/components/chatbot.blade.php',
        'resources/views/layouts/app.blade.php',
    ]

    # Upload core updated files
    for rel_path in files_to_upload:
        local_path = os.path.join(base_dir, rel_path)
        if os.path.exists(local_path):
            remote_dir = f"{REMOTE_ROOT}/{os.path.dirname(rel_path)}".replace('\\', '/')
            ensure_remote_dir(ftp, remote_dir)
            remote_path = f"{REMOTE_ROOT}/{rel_path}".replace('\\', '/')
            upload_file(ftp, local_path, remote_path)

    # Upload public/build assets
    build_dir = os.path.join(base_dir, 'public', 'build')
    if os.path.exists(build_dir):
        for root, dirs, files in os.walk(build_dir):
            rel_dir = os.path.relpath(root, base_dir).replace('\\', '/')
            remote_dir = f"{REMOTE_ROOT}/{rel_dir}"
            ensure_remote_dir(ftp, remote_dir)
            for f in files:
                local_file = os.path.join(root, f)
                remote_file = f"{remote_dir}/{f}"
                upload_file(ftp, local_file, remote_file)

    ftp.quit()
    print("\n" + "=" * 60)
    print(" [OK] CHATBOT TELAH BERHASIL DI-UPLOAD KE HOSTING!")
    print("=" * 60)

if __name__ == '__main__':
    main()
