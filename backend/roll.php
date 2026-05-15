<?php

// Start session securely
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => '',
    'secure' => false, // Set true if using HTTPS
    'httponly' => true,
    'samesite' => 'Strict'
]);
session_start();

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
require_once 'controllers/authController.php';

$action = $_GET['action'] ?? '';
$id = $_GET['id'] ?? null;

// Auth Routes (No auth required to access these endpoints)
if (in_array($action, ['login', 'logout', 'session'])) {
    switch ($action) {
        case 'login': login($pdo); break;
        case 'logout': logout(); break;
        case 'session': getSession(); break;
    }
    exit;
}

// Ensure user is authenticated for all other routes
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Not authenticated']);
    exit;
}

// Verify Admin Route (Must be authenticated as operator)
if ($action === 'verify_admin') {
    verifyAdmin($pdo);
    exit;
}

// Role-based Access Control
$role = $_SESSION['role'] ?? 'operator';
$hasTempAdminPrivilege = isset($_SESSION['temp_admin_privilege']) && $_SESSION['temp_admin_privilege'] === true;

switch ($action) {

    case 'chart':
        getChartData($pdo);
        break;

    case 'get':
        getRolls($pdo);
        break;

    case 'store':
    case 'continue':
        // Both operator and administrator can do this
        if ($action === 'store') createRoll($pdo);
        else continueRoll($pdo, $id);
        break;
    
    case 'update':
    case 'delete':
        // Only administrator or operator with temp_admin_privilege can do this
        if ($role === 'administrator' || $hasTempAdminPrivilege) {
            if ($action === 'update') updateRoll($pdo, $id);
            else deleteRoll($pdo, $id);
        } else {
            http_response_code(403);
            echo json_encode(['status' => 'error', 'message' => 'Unauthorized: Membutuhkan verifikasi administrator.']);
        }
        break;

    default:
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid action'
        ]);
}