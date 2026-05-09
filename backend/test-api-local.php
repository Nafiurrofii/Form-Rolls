<?php
/* ─────────────────────────────────────────────────────
   TEST-API-LOCAL.PHP - API Logic Verification
   ──────────────────────────────────────────────────── */

header('Content-Type: application/json');

try {
    require_once 'config/database.php';
    require_once 'models/Roll.php';
    require_once 'controllers/rollController.php';

    echo "=== API Logic Test ===\n\n";

    $rollModel = new Roll($db);
    $controller = new RollController($rollModel);

    // 1. Test Get All
    echo "1. Testing getRolls()...\n";
    $allRolls = $controller->getRolls();
    echo "Status: " . $allRolls['status'] . "\n";
    echo "Data Count: " . count($allRolls['data']) . "\n\n";

    // 2. Test Statistics
    echo "2. Testing getStatistics()...\n";
    $stats = $controller->getStatistics();
    echo "Status: " . $stats['status'] . "\n";
    echo "Total Rolls in DB: " . $stats['data']['total_rolls'] . "\n\n";

    echo "=== Logic Test Complete ===";

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
