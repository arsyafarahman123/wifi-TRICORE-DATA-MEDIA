<?php
header('Content-Type: text/plain');

$user = 'if0_43041974';
$pass = 'b33wlGdHrhtRTF';

echo "=== DETEKSI MYSQL INFINITYFREE ===\n\n";
echo "Username: $user\n";

// Check common InfinityFree hosts
$hosts = [
    'localhost',
    '127.0.0.1',
    'sql100.infinityfree.com',
    'sql101.infinityfree.com',
    'sql102.infinityfree.com',
    'sql103.infinityfree.com',
    'sql104.infinityfree.com',
    'sql105.infinityfree.com',
    'sql200.infinityfree.com',
    'sql201.infinityfree.com',
    'sql202.infinityfree.com',
    'sql203.infinityfree.com',
    'sql204.infinityfree.com',
    'sql205.infinityfree.com',
    'sql300.infinityfree.com',
    'sql301.infinityfree.com',
    'sql302.infinityfree.com',
    'sql303.infinityfree.com',
    'sql304.infinityfree.com',
    'sql305.infinityfree.com',
    'sql306.infinityfree.com',
    'sql307.infinityfree.com',
    'sql308.infinityfree.com',
    'sql309.infinityfree.com',
    'sql310.infinityfree.com',
    'sql311.infinityfree.com',
    'sql312.infinityfree.com',
    'sql313.infinityfree.com',
];

foreach ($hosts as $h) {
    try {
        $pdo = new PDO("mysql:host=$h;port=3306", $user, $pass, [
            PDO::ATTR_TIMEOUT => 2,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
        echo "[SUCCESS] Terhubung ke MySQL Host: $h\n";
        $dbs = $pdo->query('SHOW DATABASES')->fetchAll(PDO::FETCH_COLUMN);
        echo "Daftar Database:\n";
        print_r($dbs);
        break;
    } catch (Exception $e) {
        // continue
    }
}

echo "\nSelesai.\n";
