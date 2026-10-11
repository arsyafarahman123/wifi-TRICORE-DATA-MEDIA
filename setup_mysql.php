<?php
/**
 * Auto MySQL Migrator & phpMyAdmin Connector for InfinityFree
 */
define('LARAVEL_START', microtime(true));

$autoloadPath = __DIR__ . '/vendor/autoload.php';
$bootstrapPath = __DIR__ . '/bootstrap/app.php';

if (!file_exists($autoloadPath) || !file_exists($bootstrapPath)) {
    die("<h3>Error: Vendor atau bootstrap folder belum ter-upload!</h3>");
}

require $autoloadPath;
$app = require_once $bootstrapPath;

$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Auto Connect MySQL & phpMyAdmin - TRICORE</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #090d16; color: #f8fafc; padding: 40px 20px; display: flex; justify-content: center; }
        .card { max-width: 760px; width: 100%; background: #111827; border: 1px solid #1f2937; border-radius: 20px; padding: 32px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.7); }
        h1 { color: #38bdf8; font-size: 22px; margin-top: 0; display: flex; align-items: center; gap: 10px; }
        .log { background: #030712; border: 1px solid #374151; border-radius: 12px; padding: 18px; font-family: monospace; font-size: 13px; line-height: 1.6; white-space: pre-wrap; color: #a5f3fc; max-height: 420px; overflow-y: auto; margin: 20px 0; }
        .btn { display: inline-block; background: linear-gradient(to right, #06b6d4, #10b981); color: #022c22; font-weight: 700; padding: 12px 24px; border-radius: 12px; text-decoration: none; margin-right: 10px; }
        .btn-secondary { background: #374151; color: #f8fafc; padding: 12px 24px; border-radius: 12px; text-decoration: none; }
    </style>
</head>
<body>
<div class="card">
    <h1> Setup MySQL phpMyAdmin Otomatis</h1>
    <div class="log"><?php

$user = 'if0_43041974';
$pass = 'b33wlGdHrhtRTF';
$host = 'sql204.infinityfree.com';

echo "[*] Menghubungkan ke MySQL Host Anda: $host ...\n";

try {
    $pdo = new PDO("mysql:host=$host;port=3306", $user, $pass, [
        PDO::ATTR_TIMEOUT => 5,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    echo "[] BERHASIL Terhubung ke MySQL Server: $host\n";

    $stmt = $pdo->query('SHOW DATABASES');
    $dbs = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "[*] Database yang ada di akun Anda: " . implode(', ', $dbs) . "\n\n";

    $target_db = 'if0_43041974_wifi';

    echo "[*] Menggunakan Database: $target_db\n";

    Config::set('database.default', 'mysql');
    Config::set('database.connections.mysql.host', $host);
    Config::set('database.connections.mysql.database', $target_db);
    Config::set('database.connections.mysql.username', $user);
    Config::set('database.connections.mysql.password', $pass);
    DB::purge('mysql');

    echo "[*] Menjalankan Migrasi Tabel ke MySQL phpMyAdmin...\n";
    Artisan::call('migrate', ['--force' => true, '--database' => 'mysql']);
    echo Artisan::output();

    echo "[*] Memasukkan Data Awal (Admin, Paket, Coverage, Pelanggan, Tagihan)...\n";
    Artisan::call('db:seed', ['--force' => true, '--database' => 'mysql']);
    echo Artisan::output();

    // Update .env permanently
    $envPath = __DIR__ . '/.env';
    if (file_exists($envPath)) {
        $envContent = file_get_contents($envPath);
        $envContent = preg_replace('/DB_CONNECTION=.*/', "DB_CONNECTION=mysql", $envContent);
        $envContent = preg_replace('/DB_HOST=.*/', "DB_HOST=$host", $envContent);
        $envContent = preg_replace('/DB_DATABASE=.*/', "DB_DATABASE=$target_db", $envContent);
        $envContent = preg_replace('/DB_USERNAME=.*/', "DB_USERNAME=$user", $envContent);
        $envContent = preg_replace('/DB_PASSWORD=.*/', "DB_PASSWORD=$pass", $envContent);
        file_put_contents($envPath, $envContent);
        echo "[] File .env server telah diperbarui permanen ke MySQL ($target_db)!\n";
    }

    echo "\n============================================================\n";
    echo "  SEMUA TABEL SUDAH TERHUBUNG PERMANEN DI PHPMYADMIN!\n";
    echo "============================================================\n";

} catch (\Throwable $e) {
    echo "[-] Pesan: " . $e->getMessage() . "\n";
}
?></div>
    <a href="/portal" class="btn">Buka Dashboard Portal</a>
    <a href="/" class="btn btn-secondary">Buka Website</a>
</div>
</body>
</html>
