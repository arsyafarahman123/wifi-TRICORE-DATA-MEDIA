<?php
/**
 * Setup Script for InfinityFree Deployment
 * Runs migrations, seeders, and clears caches via web interface.
 */

define('LARAVEL_START', microtime(true));

$autoloadPath = __DIR__ . '/vendor/autoload.php';
$bootstrapPath = __DIR__ . '/bootstrap/app.php';

if (!file_exists($autoloadPath) || !file_exists($bootstrapPath)) {
    die("<h3>Error: Vendor atau bootstrap folder belum ter-upload dengan lengkap!</h3>");
}

require $autoloadPath;
$app = require_once $bootstrapPath;

// Boot Kernel so Facades and DB work in Laravel 11/12
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

$action = $_GET['action'] ?? 'run';
$output = [];
$status = 'success';

if ($action === 'delete') {
    if (@unlink(__FILE__)) {
        echo "<script>alert('setup.php berhasil dihapus demi keamanan!'); window.location.href='/';</script>";
        exit;
    } else {
        $output[] = "Gagal menghapus file setup.php otomatis. Silakan hapus file setup.php secara manual melalui File Manager / FTP.";
    }
} else {
    try {
        // Test Database Connection
        DB::connection()->getPdo();
        $output[] = "✅ Database terkoneksi dengan sukses ke: " . DB::connection()->getDatabaseName();

        // Run Migrations
        Artisan::call('migrate', ['--force' => true]);
        $output[] = "✅ Migrasi Database berhasil dijalankan:\n" . Artisan::output();

        // Run Seeders
        Artisan::call('db:seed', ['--force' => true]);
        $output[] = "✅ Data Seeder berhasil dimasukkan (Admin, Paket WiFi, Coverage Area, Pelanggan):\n" . Artisan::output();

        // Link Storage
        try {
            Artisan::call('storage:link');
            $output[] = "✅ Storage link berhasil dibuat.";
        } catch (\Throwable $e) {
            $output[] = "ℹ️ Storage link: " . $e->getMessage();
        }

        // Clear Caches
        Artisan::call('optimize:clear');
        $output[] = "✅ Cache & Config berhasil dibersihkan:\n" . Artisan::output();

    } catch (\Throwable $e) {
        $status = 'danger';
        $output[] = "❌ Terjadi Kesalahan: " . $e->getMessage();
        $output[] = "\nDetail Trace: " . $e->getTraceAsString();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup Layanan WiFi TriCore - InfinityFree</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #0f172a;
            color: #f8fafc;
            padding: 30px 15px;
            margin: 0;
            display: flex;
            justify-content: center;
        }
        .container {
            max-width: 800px;
            width: 100%;
            background: #1e293b;
            border-radius: 16px;
            padding: 32px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.4);
            border: 1px solid #334155;
        }
        h1 {
            font-size: 24px;
            margin-top: 0;
            color: #38bdf8;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .badge {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 9999px;
            font-weight: 700;
            font-size: 13px;
            margin-bottom: 20px;
        }
        .badge-success { background: #065f46; color: #34d399; }
        .badge-danger { background: #991b1b; color: #f87171; }
        .log-box {
            background: #090d16;
            border: 1px solid #334155;
            border-radius: 10px;
            padding: 20px;
            font-family: 'Courier New', monospace;
            font-size: 13px;
            white-space: pre-wrap;
            line-height: 1.6;
            color: #cbd5e1;
            max-height: 400px;
            overflow-y: auto;
            margin-bottom: 24px;
        }
        .btn-group {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            font-weight: 700;
            font-size: 14px;
            border-radius: 8px;
            text-decoration: none;
            cursor: pointer;
            transition: 0.2s ease;
            border: none;
        }
        .btn-primary {
            background: #0284c7;
            color: white;
        }
        .btn-primary:hover {
            background: #0369a1;
        }
        .btn-danger {
            background: #dc2626;
            color: white;
        }
        .btn-danger:hover {
            background: #b91c1c;
        }
        .alert-warning {
            background: #451a03;
            border: 1px solid #b45309;
            color: #fde68a;
            padding: 14px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🌐 Setup Layanan WiFi TriCore</h1>
        <div class="badge <?= $status === 'success' ? 'badge-success' : 'badge-danger' ?>">
            <?= $status === 'success' ? 'STATUS: BERHASIL SETUP' : 'STATUS: GAGAL' ?>
        </div>

        <div class="log-box"><?= htmlspecialchars(implode("\n\n", $output)) ?></div>

        <?php if ($status === 'success'): ?>
            <div class="alert-warning">
                ⚠️ <strong>PENTING:</strong> Demi keamanan website Anda, klik tombol <strong>"Hapus File setup.php"</strong> di bawah sebelum menggunakan website.
            </div>
            <div class="btn-group">
                <a href="/" class="btn btn-primary">Buka Website Utama &rarr;</a>
                <a href="/login" class="btn btn-primary" style="background: #4f46e5;">Buka Portal Login Mitra/Admin &rarr;</a>
                <a href="?action=delete" onclick="return confirm('Yakin ingin menghapus setup.php?')" class="btn btn-danger">Hapus File setup.php</a>
            </div>
        <?php else: ?>
            <div class="btn-group">
                <a href="?action=run" class="btn btn-primary">Coba Jalankan Lagi</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
