<?php

require_once __DIR__ . '/../models/Roll.php';

/**
 * GET DATA
 */
function getRolls($pdo) {
    try {
        $startDate = $_GET['start'] ?? null;
        $endDate = $_GET['end'] ?? null;
        $page = (int)($_GET['page'] ?? 1);
        $limit = (int)($_GET['limit'] ?? 100);
        $export = $_GET['export'] ?? false;

        $roll = new Roll($pdo);
        
        // Jika export mode, ambil semua data tanpa pagination
        if ($export) {
            $data = $roll->getAll($startDate, $endDate);
            echo json_encode([
                'status' => 'success',
                'data' => $data,
                'pagination' => [
                    'page' => 1,
                    'limit' => count($data),
                    'total' => count($data),
                    'pages' => 1
                ]
            ]);
        } else {
            // Gunakan pagination untuk normal view
            $result = $roll->getAllPaginated($page, $limit, $startDate, $endDate);
            echo json_encode([
                'status' => 'success',
                'data' => $result['data'],
                'pagination' => $result['pagination']
            ]);
        }
    } catch (Throwable $e) {
        http_response_code(500);
        echo json_encode([
            'status' => 'error',
            'message' => 'System error: ' . $e->getMessage()
        ]);
    }
}


/**
 * STORE DATA
 */
function createRoll($pdo) {
    try {
        $input = json_decode(file_get_contents("php://input"), true);
        error_log("CREATE ROLL INPUT: " . json_encode($input));

        if (!$input) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Invalid input'
            ]);
            return;
        }

        $roll = new Roll($pdo);
        $result = $roll->store($input);
        
        error_log("CREATE ROLL RESULT: " . json_encode($result));

        echo json_encode([
            'status' => 'success',
            'message' => 'Data berhasil disimpan',
            'data' => $result
        ]);
    } catch (Throwable $e) {
        error_log("CREATE ROLL ERROR: " . $e->getMessage());
        http_response_code(500);
        echo json_encode([
            'status' => 'error',
            'message' => 'System error: ' . $e->getMessage()
        ]);
    }
}


/**
 * CONTINUE ROLL
 * UPDATE LIMITED FIELDS ONLY
 */
function continueRoll($pdo, $id) {

    try {

        $input = json_decode(file_get_contents("php://input"), true);

        error_log("CONTINUE ROLL INPUT (ID: $id): " . json_encode($input));

        if (!$id) {

            echo json_encode([
                'status' => 'error',
                'message' => 'ID is required for continue'
            ]);

            return;
        }

        if (!$input) {

            echo json_encode([
                'status' => 'error',
                'message' => 'Invalid input'
            ]);

            return;
        }

        /**
         * ONLY ALLOWED FIELDS
         */
        $allowedData = [

            'jam'        => $input['jam'] ?? null,
            'roll'       => $input['roll'] ?? null,
            'group_name' => $input['group_name'] ?? null,
            'mesin'      => $input['mesin'] ?? null,
            'panjang'    => $input['panjang'] ?? null,
            'lebar'      => $input['lebar'] ?? null,
            'berat'      => $input['berat'] ?? null,
            'pic'        => $input['pic'] ?? null,

        ];

        $roll = new Roll($pdo);

        $result = $roll->continue($id, $allowedData);

        echo json_encode([
            'status' => 'success',
            'message' => 'Data berhasil dilanjutkan',
            'data' => $result
        ]);

    } catch (Throwable $e) {

        error_log("CONTINUE ROLL ERROR: " . $e->getMessage());

        http_response_code(500);

        echo json_encode([
            'status' => 'error',
            'message' => 'System error: ' . $e->getMessage()
        ]);
    }
}


/**
 * UPDATE
 */
function updateRoll($pdo, $id) {
    try {
        $input = json_decode(file_get_contents("php://input"), true);
        error_log("UPDATE ROLL INPUT (ID: $id): " . json_encode($input));

        if (!$id) {
            echo json_encode([
                'status' => 'error',
                'message' => 'ID is required for update'
            ]);
            return;
        }

        $roll = new Roll($pdo);
        $roll->update($id, $input);

        // Revoke temporary admin privilege
        if (isset($_SESSION['temp_admin_privilege'])) {
            unset($_SESSION['temp_admin_privilege']);
        }

        echo json_encode([
            'status' => 'success',
            'message' => 'Data berhasil diupdate'
        ]);
    } catch (Throwable $e) {
        error_log("UPDATE ROLL ERROR: " . $e->getMessage());
        http_response_code(500);
        echo json_encode([
            'status' => 'error',
            'message' => 'System error: ' . $e->getMessage()
        ]);
    }
}


/**
 * DELETE
 */
function deleteRoll($pdo, $id) {
    try {
        if (!$id) {
            echo json_encode([
                'status' => 'error',
                'message' => 'ID is required for delete'
            ]);
            return;
        }

        $roll = new Roll($pdo);
        $roll->delete($id);

        // Revoke temporary admin privilege
        if (isset($_SESSION['temp_admin_privilege'])) {
            unset($_SESSION['temp_admin_privilege']);
        }

        echo json_encode([
            'status' => 'success',
            'message' => 'Data berhasil dihapus'
        ]);
    } catch (Throwable $e) {
        error_log("DELETE ROLL ERROR: " . $e->getMessage());
        http_response_code(500);
        echo json_encode([
            'status' => 'error',
            'message' => 'System error: ' . $e->getMessage()
        ]);
    }
}

/**
 * GET CHART DATA
 */
function getChartData($pdo) {
    try {
        $days = isset($_GET['days']) ? (int)$_GET['days'] : 14;
        $start = isset($_GET['start']) ? $_GET['start'] : null;
        $end = isset($_GET['end']) ? $_GET['end'] : null;
        
        $roll = new Roll($pdo);
        $data = $roll->getChartData($days, $start, $end);
        echo json_encode($data);
    } catch (Throwable $e) {
        http_response_code(500);
        echo json_encode([
            'status' => 'error',
            'message' => 'System error: ' . $e->getMessage()
        ]);
    }
}
