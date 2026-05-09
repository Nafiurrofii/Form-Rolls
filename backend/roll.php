<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit;
}

require_once 'config/database.php';
require_once 'controllers/rollController.php';

$action = $_GET['action'] ?? '';
$id = $_GET['id'] ?? null;

switch ($action) {

    case 'get':
        getRolls($pdo);
        break;

    case 'store':
        createRoll($pdo);
        break;
    
    case 'update':
        updateRoll($pdo, $id);
        break;

    default:
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid action'
        ]);
}