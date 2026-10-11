<?php
/**
 * Direct Standalone MySQL Importer for InfinityFree phpMyAdmin
 * Tidak perlu dependensi Laravel, 100% PHP PDO Native
 */
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Auto Populate MySQL & phpMyAdmin - TRICORE</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #090d16; color: #f8fafc; padding: 40px 20px; display: flex; justify-content: center; }
        .card { max-width: 760px; width: 100%; background: #111827; border: 1px solid #1f2937; border-radius: 20px; padding: 32px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.7); }
        h1 { color: #38bdf8; font-size: 22px; margin-top: 0; display: flex; align-items: center; gap: 10px; }
        .log { background: #030712; border: 1px solid #374151; border-radius: 12px; padding: 18px; font-family: monospace; font-size: 13px; line-height: 1.6; white-space: pre-wrap; color: #a5f3fc; max-height: 450px; overflow-y: auto; margin: 20px 0; }
        .btn { display: inline-block; background: linear-gradient(to right, #06b6d4, #10b981); color: #022c22; font-weight: 700; padding: 12px 24px; border-radius: 12px; text-decoration: none; margin-right: 10px; }
        .btn-secondary { background: #374151; color: #f8fafc; padding: 12px 24px; border-radius: 12px; text-decoration: none; }
    </style>
</head>
<body>
<div class="card">
    <h1> Eksekusi SQL Database ke phpMyAdmin</h1>
    <div class="log"><?php

$user = 'if0_43041974';
$pass = 'b33wlGdHrhtRTF';
$host = 'sql204.infinityfree.com';
$db   = 'if0_43041974_wifi';

echo "[*] Menghubungkan ke MySQL Database ($db) di $host ...\n";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_TIMEOUT => 10,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
    ]);
    echo "[] BERHASIL Terhubung ke MySQL Database ($db)!\n\n";

    $sqlFile = __DIR__ . '/tricore_database_full.sql';
    if (!file_exists($sqlFile)) {
        die("[-] File tricore_database_full.sql tidak ditemukan di server!\n");
    }

    echo "[*] Membaca file SQL dan mengeksekusi struktur tabel serta data...\n";
    $sqlContent = file_get_contents($sqlFile);

    // Disable foreign key checks momentarily for clean import
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0;");

    // Execute queries
    $pdo->exec($sqlContent);

    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1;");

    echo "[] SEMUA STRUKTUR TABEL & DATA ASLI BERHASIL DIBUAT!\n\n";

    // Verifikasi tabel yang dibuat
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

    echo " Daftar Tabel Aktif di phpMyAdmin:\n";
    foreach ($tables as $t) {
        $countStmt = $pdo->query("SELECT COUNT(*) FROM `$t`");
        $count = $countStmt->fetchColumn();
        echo "  - `$t` : $count baris data\n";
    }

    // Update file .env
    $envPath = __DIR__ . '/.env';
    if (file_exists($envPath)) {
        $env = file_get_contents($envPath);
        $env = preg_replace('/DB_CONNECTION=.*/', "DB_CONNECTION=mysql", $env);
        $env = preg_replace('/DB_HOST=.*/', "DB_HOST=$host", $env);
        $env = preg_replace('/DB_DATABASE=.*/', "DB_DATABASE=$db", $env);
        $env = preg_replace('/DB_USERNAME=.*/', "DB_USERNAME=$user", $env);
        $env = preg_replace('/DB_PASSWORD=.*/', "DB_PASSWORD=$pass", $env);
        file_put_contents($envPath, $env);
        echo "\n[] Konfigurasi server .env sudah diarahkan ke MySQL phpMyAdmin!\n";
    }

    echo "\n============================================================\n";
    echo "  SELESAI! Silakan buka / refresh phpMyAdmin Anda.\n";
    echo "============================================================\n";

} catch (\Throwable $e) {
    echo "[-] Terjadi Error: " . $e->getMessage() . "\n";
}
?></div>
    <a href="/portal" class="btn">Buka Portal Admin/Mitra</a>
    <a href="/" class="btn btn-secondary">Buka Website TRICORE</a>
</div>
</body>
</html>
