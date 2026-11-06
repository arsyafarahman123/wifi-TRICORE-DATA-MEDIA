import os
import sys
import ftplib
import time

FTP_HOST = 'ftpupload.net'
FTP_USER = 'if0_43041974'
FTP_PASS = 'b33wlGdHrhtRTF'
REMOTE_ROOT = '/htdocs'

def connect_ftp():
    print(f"[*] Menghubungkan ke FTP ({FTP_HOST})...", flush=True)
    ftp = ftplib.FTP(FTP_HOST, timeout=30)
    ftp.login(FTP_USER, FTP_PASS)
    ftp.set_pasv(True)
    print(f"[OK] Berhasil login sebagai {FTP_USER}", flush=True)
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
                print(f"[+] Membuat direktori remote: {part}", flush=True)
            except Exception:
                pass

def upload_single_file(ftp, local_path, remote_rel_path, max_retries=3):
    remote_dir = f"{REMOTE_ROOT}/{os.path.dirname(remote_rel_path)}".replace('\\', '/').rstrip('/')
    filename = os.path.basename(remote_rel_path)

    for attempt in range(max_retries):
        try:
            ensure_remote_dir(ftp, remote_dir)
            with open(local_path, 'rb') as f:
                ftp.storbinary(f'STOR {filename}', f)
            print(f"[OK] Uploaded -> {remote_rel_path}", flush=True)
            return True
        except Exception as e:
            print(f"[RETRY {attempt+1}/{max_retries}] Gagal {remote_rel_path}: {e}", flush=True)
            time.sleep(2)
            try:
                ftp.cwd('/')
            except Exception:
                pass
    return False

def main():
    base_dir = os.path.dirname(os.path.abspath(__file__))
    ftp = connect_ftp()

    # Core files to deploy
    files_to_deploy = [
        # Services (Recommendation Engine)
        ('app/Services/PackageRecommendationEngine.php', 'app/Services/PackageRecommendationEngine.php'),

        # Controllers
        ('app/Http/Controllers/ChatbotController.php', 'app/Http/Controllers/ChatbotController.php'),
        ('app/Http/Controllers/CustomerPortalController.php', 'app/Http/Controllers/CustomerPortalController.php'),
        ('app/Http/Controllers/InvoiceManagementController.php', 'app/Http/Controllers/InvoiceManagementController.php'),
        ('app/Http/Controllers/PackageManagementController.php', 'app/Http/Controllers/PackageManagementController.php'),

        # Models
        ('app/Models/Invoice.php', 'app/Models/Invoice.php'),

        # Routes
        ('routes/web.php', 'routes/web.php'),

        # Migrations & Database update
        ('database/migrations/2026_11_04_000001_add_payment_verification_to_invoices_table.php', 'database/migrations/2026_11_04_000001_add_payment_verification_to_invoices_table.php'),
        ('tricore_database_full.sql', 'tricore_database_full.sql'),
        ('update_live_db.php', 'update_live_db.php'),

        # Views
        ('resources/views/components/chatbot.blade.php', 'resources/views/components/chatbot.blade.php'),
        ('resources/views/portal/payment.blade.php', 'resources/views/portal/payment.blade.php'),
        ('resources/views/portal/bill-result.blade.php', 'resources/views/portal/bill-result.blade.php'),
        ('resources/views/portal/invoices/index.blade.php', 'resources/views/portal/invoices/index.blade.php'),
        ('resources/views/home.blade.php', 'resources/views/home.blade.php'),
    ]

    print(f"\n[*] Memulai upload {len(files_to_deploy)} file ke hosting InfinityFree (http://wifi-tricore.rf.gd)...\n", flush=True)
    success = 0
    for local_rel, remote_rel in files_to_deploy:
        local_path = os.path.join(base_dir, local_rel)
        if not os.path.exists(local_path):
            print(f"[!] File lokal tidak ditemukan: {local_path}", flush=True)
            continue
        if upload_single_file(ftp, local_path, remote_rel):
            success += 1

    # Ensure uploads directory on remote server
    ensure_remote_dir(ftp, f"{REMOTE_ROOT}/public/uploads/proofs")
    ensure_remote_dir(ftp, f"{REMOTE_ROOT}/uploads/proofs")

    ftp.quit()
    print(f"\n[DONE] Selesai! Berhasil mengunggah {success}/{len(files_to_deploy)} file ke server hosting.", flush=True)

if __name__ == '__main__':
    main()
