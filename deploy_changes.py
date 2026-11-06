import os
import sys
import ftplib
import time

FTP_HOST = 'ftpupload.net'
FTP_USER = 'if0_43041974'
FTP_PASS = 'b33wlGdHrhtRTF'
REMOTE_ROOT = '/htdocs'

def connect_ftp():
    print(f"Connecting to FTP ({FTP_HOST})...", flush=True)
    ftp = ftplib.FTP(FTP_HOST, timeout=30)
    ftp.login(FTP_USER, FTP_PASS)
    ftp.set_pasv(True)
    print(f"Logged in successfully as {FTP_USER}", flush=True)
    return ftp

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
                print(f"Created folder: {part}", flush=True)
            except Exception as e:
                print(f"Error creating/entering {part}: {e}", flush=True)

def upload_single_file(ftp, local_path, rel_path, max_retries=3):
    remote_dir = f"{REMOTE_ROOT}/{os.path.dirname(rel_path)}".replace('\\', '/').rstrip('/')
    filename = os.path.basename(rel_path)
    
    for attempt in range(max_retries):
        try:
            ensure_remote_dir(ftp, remote_dir)
            with open(local_path, 'rb') as f:
                ftp.storbinary(f'STOR {filename}', f)
            print(f"[OK] Uploaded: {rel_path}", flush=True)
            return True
        except Exception as e:
            print(f"[RETRY {attempt+1}/{max_retries}] Failed {rel_path}: {e}", flush=True)
            time.sleep(2)
            try:
                ftp.cwd('/')
            except Exception:
                pass
    return False

def main():
    base_dir = os.path.dirname(os.path.abspath(__file__))
    ftp = connect_ftp()

    files = [
        'routes/web.php',
        'app/Http/Controllers/ChatbotController.php',
        'resources/views/components/chatbot.blade.php',
        'resources/views/home.blade.php',
        'resources/views/layouts/app.blade.php',
        'resources/views/layouts/portal.blade.php',
        'resources/views/portal/dashboard.blade.php',
        'resources/views/portal/customers/show.blade.php',
        'resources/views/portal/invoices/index.blade.php',
        'resources/views/portal/invoices/print.blade.php',
    ]

    # Also include public/build files
    build_dir = os.path.join(base_dir, 'public', 'build')
    if os.path.exists(build_dir):
        for root, _, filenames in os.walk(build_dir):
            for fn in filenames:
                full_local = os.path.join(root, fn)
                rel = os.path.relpath(full_local, base_dir).replace('\\', '/')
                files.append(rel)

    print(f"Total files to deploy: {len(files)}", flush=True)
    success_count = 0
    for rel_path in files:
        local_path = os.path.join(base_dir, rel_path)
        if os.path.exists(local_path):
            if upload_single_file(ftp, local_path, rel_path):
                success_count += 1
        else:
            print(f"Skipping missing: {local_path}", flush=True)

    try:
        ftp.quit()
    except Exception:
        pass

    print("=" * 60, flush=True)
    print(f"DEPLOY COMPLETE! Successfully uploaded {success_count}/{len(files)} files to {FTP_HOST}", flush=True)
    print("=" * 60, flush=True)

if __name__ == '__main__':
    main()
