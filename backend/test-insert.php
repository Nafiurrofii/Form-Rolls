<?php
/* ─────────────────────────────────────────────────────
   TEST-INSERT.PHP - Test Inserting Data
   ──────────────────────────────────────────────────── */

require_once 'config/database.php';
require_once 'models/Roll.php';
require_once 'controllers/rollController.php';

echo "=== Testing Insert Data ===\n\n";

$rollModel = new Roll($db);

// Data dummy untuk ditest
$data = [
    'tanggal' => date('Y-m-d'),
    'jam' => date('H:i:s'),
    'roll' => 1,
    'group_name' => 'A',
    'mesin' => 'M01',
    'nama' => 'TEST ROLL CLI',
    'denier' => 1500,
    'panjang' => 100,
    'lebar' => 50,
    'anyam' => 'TEST',
    'berat' => 10.5,
    'trace_code' => 'TRC-TEST-001',
    'keterangan' => 'Data ditambahkan melalui script testing',
    'pic' => 'SYSTEM'
];

try {
    $response = $rollModel->store($data);
    if ($response['status'] === 'success') {
        echo "SUCCESS: Data berhasil dimasukkan!\n";
        echo "ID Baru: " . $response['id'] . "\n\n";
        
        // Cek lagi jumlah datanya
        $count = $db->query("SELECT COUNT(*) FROM rolls")->fetchColumn();
        echo "Jumlah total data sekarang: " . $count . "\n";
    } else {
        echo "FAILED: Gagal memasukkan data.\n";
    }
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}

echo "\n=== Insert Test Complete ===";
