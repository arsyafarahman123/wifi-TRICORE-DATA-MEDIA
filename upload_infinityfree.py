import os
import sys
import ftplib
import time

if hasattr(sys.stdout, 'reconfigure'):
    sys.stdout.reconfigure(encoding='utf-8')

# ==========================================
# KONFIGURASI FTP INFINITYFREE ANDA
# (Silakan isi setelah membuat akun di InfinityFree)
# ==========================================
FTP_HOST = os.environ.get('IF_FTP_HOST', 'ftpupload.net')
FTP_USER = os.environ.get('IF_FTP_USER', '')  # Contoh: if0_12345678
FTP_PASS = os.environ.get('IF_FTP_PASS', '')  # Password akun InfinityFree
REMOTE_ROOT = '/htdocs'

EXCLUDE_DIRS = {
    '.git', '.github', 'node_modules', 'tests', '.claude', 'docker', 'scratch'
}

EXCLUDE_FILES = {
    '.env',  # Kita gunakan .env.production yang di-rename jadi .env
    '.gitignore', '.gitattributes', '.editorconfig', 'phpunit.xml',
    'package-lock.json', 'vite.config.js', 'render.yaml', 'nixpacks.toml'
}

def connect_ftp():
    if not FTP_USER or not FTP_PASS:
        print("[!] Masukkan FTP_USER dan FTP_PASS terlebih dahulu di file upload_infinityfree.py atau jalankan deploy-infinityfree.bat!")
        sys.exit(1)
    
    print(f"[*] Menghubungkan ke {FTP_HOST}...")
    ftp = ftplib.FTP(FTP_HOST, timeout=30)
    ftp.login(FTP_USER, FTP_PASS)
    ftp.set_pasv(True)
    print(f"[✓] Berhasil login sebagai {FTP_USER}")
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
            except Exception as e:
                pass

def upload_file(ftp, local_path, remote_path, max_retries=3):
    for attempt in range(max_retries):
        try:
            with open(local_path, 'rb') as f:
                ftp.storbinary(f'STOR {remote_path}', f)
            print(f"[✓] Upload: {remote_path}")
            return True
        except Exception as e:
            print(f"[!] Gagal upload {remote_path} (Percobaan {attempt+1}/{max_retries}): {e}")
            time.sleep(2)
    return False

def run_upload():
    print("=" * 60)
    print("   AUTO-UPLOAD PROJEK LAYANAN WIFI KE INFINITYFREE")
    print("=" * 60)
    
    ftp = connect_ftp()
    
    base_dir = os.path.dirname(os.path.abspath(__file__))
    
    # 1. Pastikan upload .env.production sebagai .env di server
    env_prod = os.path.join(base_dir, '.env.production')
    if os.path.exists(env_prod):
        print("[*] Mengunggah .env.production -> /htdocs/.env")
        ensure_remote_dir(ftp, REMOTE_ROOT)
        upload_file(ftp, env_prod, f"{REMOTE_ROOT}/.env")

    # 2. Upload root files penting (.htaccess, index.php, setup.php, artisan)
    root_files = ['.htaccess', 'index.php', 'setup.php', 'artisan', 'composer.json']
    for rf in root_files:
        p = os.path.join(base_dir, rf)
        if os.path.exists(p):
            upload_file(ftp, p, f"{REMOTE_ROOT}/{rf}")

    # 3. Upload direktori proyek
    target_dirs = ['app', 'bootstrap', 'config', 'database', 'public', 'resources', 'routes', 'vendor']
    
    for t_dir in target_dirs:
        dir_path = os.path.join(base_dir, t_dir)
        if not os.path.exists(dir_path):
            continue
            
        print(f"\n[*] Mengunggah direktori: {t_dir}/ ...")
        for root, dirs, files in os.walk(dir_path):
            # Exclude
            dirs[:] = [d for d in dirs if d not in EXCLUDE_DIRS]
            
            rel_dir = os.path.relpath(root, base_dir).replace('\\', '/')
            remote_dir = f"{REMOTE_ROOT}/{rel_dir}"
            ensure_remote_dir(ftp, remote_dir)
            
            for f in files:
                if f in EXCLUDE_FILES:
                    continue
                local_file = os.path.join(root, f)
                remote_file = f"{remote_dir}/{f}"
                upload_file(ftp, local_file, remote_file)
                
    ftp.quit()
    print("\n" + "=" * 60)
    print(" [✓] SEMUA FILE BERHASIL DI-UPLOAD KE INFINITYFREE!")
    print(" Langkah berikutnya: Buka https://nama-subdomain-anda/setup.php")
    print("=" * 60)

if __name__ == '__main__':
    run_upload()
