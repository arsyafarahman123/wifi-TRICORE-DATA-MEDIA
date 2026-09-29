import os
import sys
import ftplib
import time
import urllib.request
import ssl

if hasattr(sys.stdout, 'reconfigure'):
    sys.stdout.reconfigure(encoding='utf-8')

FTP_HOST = 'ftpupload.net'
FTP_USER = 'if0_43041974'
FTP_PASS = 'b33wlGdHrhtRTF'
REMOTE_ROOT = '/htdocs'

EXCLUDE_DIRS = {
    '.git', '.github', 'node_modules', 'tests', '.claude', 'docker', 'scratch', 'vendor'
}

EXCLUDE_FILES = {
    '.env', '.env.example', '.gitignore', '.gitattributes', '.editorconfig', 
    'phpunit.xml', 'package-lock.json', 'vite.config.js', 'render.yaml', 
    'nixpacks.toml', 'boost.json', 'CLAUDE.md', 'README.md', 'Dockerfile'
}

def connect_ftp():
    print(f"[*] Menghubungkan ke FTP ({FTP_HOST})...")
    ftp = ftplib.FTP(FTP_HOST, timeout=60)
    ftp.login(FTP_USER, FTP_PASS)
    ftp.set_pasv(True)
    print(f"[✓] Berhasil login FTP sebagai {FTP_USER}")
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
            except Exception:
                pass

def upload_file(ftp, local_path, remote_path, max_retries=3):
    for attempt in range(max_retries):
        try:
            with open(local_path, 'rb') as f:
                ftp.storbinary(f'STOR {remote_path}', f)
            print(f"[✓] Terupload: {remote_path}")
            return True
        except Exception as e:
            print(f"[!] Gagal ({attempt+1}/{max_retries}) {remote_path}: {e}")
            time.sleep(2)
    return False

def main():
    base_dir = os.path.dirname(os.path.abspath(__file__))
    ftp = connect_ftp()

    # 1. Pastikan folder htdocs ada
    ensure_remote_dir(ftp, REMOTE_ROOT)

    # 2. Upload .env.production sebagai .env
    env_file = os.path.join(base_dir, '.env.production')
    if os.path.exists(env_file):
        print("\n[*] Mengunggah .env.production -> /htdocs/.env")
        upload_file(ftp, env_file, f"{REMOTE_ROOT}/.env")

    # 3. Upload file root penting
    root_files = ['.htaccess', 'index.php', 'setup.php', 'artisan', 'composer.json', 'extract.php']
    print("\n[*] Mengunggah file root...")
    for rf in root_files:
        p = os.path.join(base_dir, rf)
        if os.path.exists(p):
            upload_file(ftp, p, f"{REMOTE_ROOT}/{rf}")

    # 4. Upload vendor.zip (hanya 7.7MB!)
    vendor_zip = os.path.join(base_dir, 'vendor.zip')
    if os.path.exists(vendor_zip):
        print("\n[*] Mengunggah paket vendor.zip (7.7 MB)...")
        upload_file(ftp, vendor_zip, f"{REMOTE_ROOT}/vendor.zip")

    # 5. Upload folder proyek (app, bootstrap, config, database, public, resources, routes, storage)
    target_dirs = ['app', 'bootstrap', 'config', 'database', 'public', 'resources', 'routes', 'storage']
    for t_dir in target_dirs:
        dir_path = os.path.join(base_dir, t_dir)
        if not os.path.exists(dir_path):
            continue
        print(f"\n[*] Mengunggah folder: {t_dir}/ ...")
        for root, dirs, files in os.walk(dir_path):
            dirs[:] = [d for d in dirs if d not in EXCLUDE_DIRS]
            rel_dir = os.path.relpath(root, base_dir).replace('\\', '/')
            remote_dir = f"{REMOTE_ROOT}/{rel_dir}"
            ensure_remote_dir(ftp, remote_dir)

            for f in files:
                if f in EXCLUDE_FILES or f.endswith('.zip') or f.endswith('.tmp'):
                    continue
                local_file = os.path.join(root, f)
                remote_file = f"{remote_dir}/{f}"
                upload_file(ftp, local_file, remote_file)

    ftp.quit()
    print("\n" + "=" * 60)
    print(" [✓] SEMUA FILE TELAH BERHASIL DI-UPLOAD KE HOSTING!")
    print("=" * 60)

if __name__ == '__main__':
    main()
