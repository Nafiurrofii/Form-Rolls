<?php

require_once __DIR__ . '/../models/Roll.php';

/**
 * GET DATA
 */
function getRolls($pdo) {
    try {
        $roll = new Roll($pdo);
        $data = $roll->getAll();

        echo json_encode([
            'status' => 'success',
            'data' => $data
        ]);
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
 * UPDATE
 */
function updateRoll($pdo, $id) {

    $input = json_decode(file_get_contents("php://input"), true);

    $roll = new Roll($pdo);

    $roll->update($id, $input);

    echo json_encode([
        'status' => 'success',
        'message' => 'Data berhasil diupdate'
    ]);
}

/**
 * DELETE
 */
function deleteRoll($pdo, $id) {

    $roll = new Roll($pdo);

    $roll->delete($id);

    echo json_encode([
        'status' => 'success',
        'message' => 'Data berhasil dihapus'
    ]);
}

