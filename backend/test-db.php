<?php
/* ─────────────────────────────────────────────────────
   TEST-DB.PHP - Database Connection Diagnostic Tool
   ──────────────────────────────────────────────────── */

header('Content-Type: application/json');

require_once 'config/database.php';

$results = [
    'step_1_server_connection' => 'PENDING',
    'step_2_database_selection' => 'PENDING',
    'step_3_table_existence' => 'PENDING',
    'details' => []
];

try {
    // Step 1 & 2: Dijalankan otomatis saat require database.php
    // Karena database.php langsung memanggil getDatabaseConnection()
    
    if ($db instanceof PDO) {
        $results['step_1_server_connection'] = 'SUCCESS';
        $results['step_2_database_selection'] = 'SUCCESS';
        $results['details'][] = "Berhasil terhubung ke host: " . DB_HOST;
        $results['details'][] = "Berhasil masuk ke database: " . DB_NAME;
    }

    // Step 3: Cek Tabel
    $query = $db->query("SHOW TABLES LIKE 'rolls'");
    if ($query->rowCount() > 0) {
        $results['step_3_table_existence'] = 'SUCCESS';
        
        // Bonus: Cek jumlah data
        $count = $db->query("SELECT COUNT(*) FROM rolls")->fetchColumn();
        $results['details'][] = "Tabel 'rolls' ditemukan. Jumlah data saat ini: " . $count;
    } else {
        $results['step_3_table_existence'] = 'FAILED';
        $results['details'][] = "Tabel 'rolls' tidak ditemukan. Pastikan sudah menjalankan schema.sql";
    }

    echo json_encode([
        'status' => 'success',
        'message' => 'Diagnostic complete',
        'results' => $results
    ], JSON_PRETTY_PRINT);

} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Diagnostic failed',
        'error' => $e->getMessage(),
        'results' => $results
    ], JSON_PRETTY_PRINT);
}
