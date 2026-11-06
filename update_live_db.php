<?php
/**
 * Update script for InfinityFree live database
 */
header('Content-Type: text/plain; charset=utf-8');

$user = 'if0_43041974';
$pass = 'b33wlGdHrhtRTF';
$host = 'sql204.infinityfree.com';
$db   = 'if0_43041974_wifi';

echo "[*] Connecting to MySQL at {$host}...\n";

try {
    $pdo = new PDO("mysql:host={$host};dbname={$db};charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_TIMEOUT => 10,
    ]);
    echo "[OK] Connected to database {$db}.\n\n";

    // Check if columns exist
    $cols = $pdo->query("SHOW COLUMNS FROM invoices LIKE 'payment_proof'")->fetchAll();
    if (empty($cols)) {
        echo "[*] Adding payment verification columns to invoices table...\n";
        $pdo->exec("ALTER TABLE `invoices` 
            ADD COLUMN `payment_proof` VARCHAR(255) NULL AFTER `payment_reference`,
            ADD COLUMN `proof_uploaded_at` TIMESTAMP NULL AFTER `payment_proof`,
            ADD COLUMN `rejection_reason` TEXT NULL AFTER `proof_uploaded_at`
        ");
        echo "[OK] Columns payment_proof, proof_uploaded_at, rejection_reason successfully added!\n";
    } else {
        echo "[OK] Columns already exist in invoices table.\n";
    }

    echo "\n=== ALL LIVE DATABASE UPDATES COMPLETED ===\n";
} catch (Exception $e) {
    echo "[-] Error: " . $e->getMessage() . "\n";
}

