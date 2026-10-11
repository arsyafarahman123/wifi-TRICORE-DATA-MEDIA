import os
import sys
import ftplib
import time

if hasattr(sys.stdout, 'reconfigure'):
    sys.stdout.reconfigure(encoding='utf-8')

FTP_HOST = os.environ.get('IF_FTP_HOST', 'ftpupload.net')
FTP_USER = os.environ.get('IF_FTP_USER', '')
FTP_PASS = os.environ.get('IF_FTP_PASS', '')
REMOTE_ROOT = '/htdocs'

def connect_ftp():
    if not FTP_USER or not FTP_PASS:
        print("[!] Set IF_FTP_USER dan IF_FTP_PASS terlebih dahulu!")
        sys.exit(1)
    print(f"[*] Menghubungkan ke {FTP_HOST}...")
    ftp = ftplib.FTP(FTP_HOST, timeout=30)
    ftp.login(FTP_USER, FTP_PASS)
    ftp.set_pasv(True)
    print(f"[v] Login berhasil: {FTP_USER}")
    return ftp

def upload_file(ftp, local_path, remote_path, max_retries=3):
    for attempt in range(max_retries):
        try:
            with open(local_path, 'rb') as f:
                ftp.storbinary(f'STOR {remote_path}', f)
            print(f"[v] Upload: {remote_path}")
            return True
        except Exception as e:
            print(f"[!] Gagal upload (Percobaan {attempt+1}): {e}")
            time.sleep(2)
    return False

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
            except:
                pass

def run():
    print("=" * 60)
    print("  UPLOAD MYSQL SETUP + DATABASE FILES KE INFINITYFREE")
    print("=" * 60)
    
    ftp = connect_ftp()
    base_dir = os.path.dirname(os.path.abspath(__file__))
    
    # 1. Upload .env.production as .env
    env_prod = os.path.join(base_dir, '.env.production')
    if os.path.exists(env_prod):
        print("\n[*] Mengunggah .env.production -> /htdocs/.env")
        upload_file(ftp, env_prod, f"{REMOTE_ROOT}/.env")
    
    # 2. Upload setup files
    for fn in ['setup_mysql.php', 'import_sql_direct.php', 'tricore_database_full.sql']:
        p = os.path.join(base_dir, fn)
        if os.path.exists(p):
            print(f"[*] Mengunggah {fn} ...")
            upload_file(ftp, p, f"{REMOTE_ROOT}/{fn}")
    
    # 3. Upload database/migrations
    mig_dir = os.path.join(base_dir, 'database', 'migrations')
    if os.path.exists(mig_dir):
        print("\n[*] Mengunggah database/migrations/ ...")
        ensure_remote_dir(ftp, f"{REMOTE_ROOT}/database/migrations")
        for f in os.listdir(mig_dir):
            local_file = os.path.join(mig_dir, f)
            if os.path.isfile(local_file):
                upload_file(ftp, local_file, f"{REMOTE_ROOT}/database/migrations/{f}")
    
    # 4. Upload database/seeders
    seed_dir = os.path.join(base_dir, 'database', 'seeders')
    if os.path.exists(seed_dir):
        print("\n[*] Mengunggah database/seeders/ ...")
        ensure_remote_dir(ftp, f"{REMOTE_ROOT}/database/seeders")
        for f in os.listdir(seed_dir):
            local_file = os.path.join(seed_dir, f)
            if os.path.isfile(local_file):
                upload_file(ftp, local_file, f"{REMOTE_ROOT}/database/seeders/{f}")
    
    # 5. Upload database/factories (if any)
    fac_dir = os.path.join(base_dir, 'database', 'factories')
    if os.path.exists(fac_dir):
        print("\n[*] Mengunggah database/factories/ ...")
        ensure_remote_dir(ftp, f"{REMOTE_ROOT}/database/factories")
        for f in os.listdir(fac_dir):
            local_file = os.path.join(fac_dir, f)
            if os.path.isfile(local_file):
                upload_file(ftp, local_file, f"{REMOTE_ROOT}/database/factories/{f}")

    # 6. Upload config/database.php to make sure mysql connection is defined
    config_db = os.path.join(base_dir, 'config', 'database.php')
    if os.path.exists(config_db):
        print("\n[*] Mengunggah config/database.php")
        ensure_remote_dir(ftp, f"{REMOTE_ROOT}/config")
        upload_file(ftp, config_db, f"{REMOTE_ROOT}/config/database.php")

    ftp.quit()
    print("\n" + "=" * 60)
    print(" [v] UPLOAD SELESAI!")
    print(" Buka: http://wifi-tricore.rf.gd/setup_mysql.php")
    print("=" * 60)

if __name__ == '__main__':
    run()
