import os
import sys
import ftplib
import time

FTP_HOST = 'ftpupload.net'
FTP_USER = 'if0_43041974'
FTP_PASS = 'b33wlGdHrhtRTF'
REMOTE_ROOT = '/htdocs'

def connect_ftp(max_attempts=5):
    for i in range(max_attempts):
        try:
            print(f"[*] Menghubungi FTP ({FTP_HOST}) percobaan {i+1}/{max_attempts}...", flush=True)
            ftp = ftplib.FTP(FTP_HOST, timeout=60)
            ftp.login(FTP_USER, FTP_PASS)
            ftp.set_pasv(True)
            print(f"[v] Berhasil login sebagai {FTP_USER}", flush=True)
            return ftp
        except Exception as e:
            print(f"[!] Gagal login ({e}), tunggu 3 detik...", flush=True)
            time.sleep(3)
    raise Exception("Tidak dapat terhubung ke FTP setelah beberapa percobaan.")

def ensure_remote_dir(ftp, remote_dir):
    ftp.cwd('/')
    parts = remote_dir.strip('/').split('/')
    for part in parts:
        if not part:
            continue
        try:
            ftp.cwd(part)
        except ftplib.error_perm:
            try:
                ftp.mkd(part)
                ftp.cwd(part)
                print(f"[+] Folder dibuat: {part}", flush=True)
            except Exception as e:
                print(f"[!] Error masuk folder {part}: {e}", flush=True)

def upload_single_file(ftp, local_path, rel_path, max_retries=3):
    remote_dir = f"{REMOTE_ROOT}/{os.path.dirname(rel_path)}".replace('\\', '/').rstrip('/')
    filename = os.path.basename(rel_path)
    
    for attempt in range(max_retries):
        try:
            ensure_remote_dir(ftp, remote_dir)
            with open(local_path, 'rb') as f:
                ftp.storbinary(f'STOR {filename}', f)
            print(f"[v] Terupload: {rel_path}", flush=True)
            return True
        except Exception as e:
            print(f"[!] Percobaan {attempt+1}/{max_retries} gagal untuk {rel_path}: {e}", flush=True)
            time.sleep(2)
            try:
                ftp.cwd('/')
            except Exception:
                pass
    return False

def clear_remote_cache(ftp):
    cache_dir = f"{REMOTE_ROOT}/bootstrap/cache"
    try:
        ftp.cwd(cache_dir)
        files = []
        ftp.retrlines('NLST', files.append)
        for fn in files:
            if fn in ['.', '..', '.gitignore']:
                continue
            if fn.endswith('.php'):
                try:
                    ftp.delete(fn)
                    print(f"[*] Hapus remote cache: {fn}", flush=True)
                except Exception as e:
                    print(f"[!] Gagal hapus cache {fn}: {e}", flush=True)
    except Exception as e:
        print(f"[!] Gagal akses cache dir: {e}", flush=True)

def main():
    base_dir = os.path.dirname(os.path.abspath(__file__))
    ftp = connect_ftp()

    files = [
        'app/Http/Controllers/DiagnosisController.php',
        'app/Http/Controllers/ChatbotController.php',
        'resources/views/portal/diagnosis.blade.php',
        'resources/views/components/chatbot.blade.php',
        'routes/web.php',
        'bootstrap/app.php',
        'resources/views/layouts/app.blade.php',
    ]

    print("=" * 60, flush=True)
    print("  MEMULAI UPLOAD FITUR AI & CHATBOT KE INFINITYFREE", flush=True)
    print("=" * 60, flush=True)

    success_count = 0
    for rel_path in files:
        local_path = os.path.join(base_dir, rel_path)
        if os.path.exists(local_path):
            if upload_single_file(ftp, local_path, rel_path):
                success_count += 1
        else:
            print(f"[!] File lokal tidak ada: {local_path}", flush=True)

    print("\n[*] Membersihkan cache Laravel di remote server...", flush=True)
    clear_remote_cache(ftp)

    try:
        ftp.quit()
    except Exception:
        pass

    print("=" * 60, flush=True)
    print(f"[SUCCESS] BERHASIL UPLOAD {success_count}/{len(files)} FILE KE HOSTING!", flush=True)
    print("=" * 60, flush=True)

if __name__ == '__main__':
    main()
