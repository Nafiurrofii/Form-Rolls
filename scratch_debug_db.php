<?php
require_once 'backend/config/database.php';

try {
    echo "--- LAST 5 RECORDS ---\n";
    $stmt = $pdo->query("SELECT id, tanggal, roll, register_no FROM rolls ORDER BY id DESC LIMIT 5");
    $rows = $stmt->fetchAll();
    echo json_encode($rows, JSON_PRETTY_PRINT) . "\n\n";

    echo "--- TRIGGERS ---\n";
    $stmt = $pdo->query("SHOW TRIGGERS LIKE 'rolls'");
    $triggers = $stmt->fetchAll();
    echo json_encode($triggers, JSON_PRETTY_PRINT) . "\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
