<?php

require_once __DIR__ . '/../models/User.php';

function login($pdo) {
    try {
        $input = json_decode(file_get_contents("php://input"), true);
        $username = $input['username'] ?? '';
        $password = $input['password'] ?? '';

        if (!$username || !$password) {
            echo json_encode(['status' => 'error', 'message' => 'Username dan password harus diisi']);
            return;
        }

        $userModel = new User($pdo);
        $user = $userModel->findByUsername($username);

        if ($user && password_verify($password, $user['password'])) {
            // Set session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            echo json_encode([
                'status' => 'success',
                'message' => 'Login berhasil',
                'user' => [
                    'username' => $user['username'],
                    'role' => $user['role']
                ]
            ]);
        } else {
            http_response_code(401);
            echo json_encode(['status' => 'error', 'message' => 'Username atau password salah']);
        }
    } catch (Throwable $e) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'System error: ' . $e->getMessage()]);
    }
}

function logout() {
    session_destroy();
    echo json_encode(['status' => 'success', 'message' => 'Logout berhasil']);
}

function getSession() {
    if (isset($_SESSION['user_id'])) {
        echo json_encode([
            'status' => 'success',
            'user' => [
                'username' => $_SESSION['username'],
                'role' => $_SESSION['role']
            ]
        ]);
    } else {
        http_response_code(401);
        echo json_encode(['status' => 'error', 'message' => 'Not authenticated']);
    }
}

function verifyAdmin($pdo) {
    try {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'operator') {
            http_response_code(403);
            echo json_encode(['status' => 'error', 'message' => 'Hanya operator yang membutuhkan verifikasi ini']);
            return;
        }

        $input = json_decode(file_get_contents("php://input"), true);
        $username = $input['username'] ?? '';
        $password = $input['password'] ?? '';

        if (!$username || !$password) {
            echo json_encode(['status' => 'error', 'message' => 'Username dan password admin harus diisi']);
            return;
        }

        $userModel = new User($pdo);
        $user = $userModel->findByUsername($username);

        if ($user && $user['role'] === 'administrator' && password_verify($password, $user['password'])) {
            // Berikan privilege khusus sementara
            $_SESSION['temp_admin_privilege'] = true;

            echo json_encode([
                'status' => 'success',
                'message' => 'Verifikasi Admin berhasil'
            ]);
        } else {
            http_response_code(401);
            echo json_encode(['status' => 'error', 'message' => 'Kredensial Admin tidak valid']);
        }
    } catch (Throwable $e) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'System error: ' . $e->getMessage()]);
    }
}
