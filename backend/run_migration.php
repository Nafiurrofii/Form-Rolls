<?php

require_once __DIR__ . '/config/database.php';

if ($argc < 2) {
    fwrite(STDERR, "Usage: php backend/run_migration.php <sql-file>\n");
    exit(1);
}

$inputPath = $argv[1];
$resolvedPath = realpath($inputPath);

if ($resolvedPath === false || !is_file($resolvedPath)) {
    fwrite(STDERR, "SQL file not found: {$inputPath}\n");
    exit(1);
}

$sql = file_get_contents($resolvedPath);
if ($sql === false) {
    fwrite(STDERR, "Failed to read SQL file: {$resolvedPath}\n");
    exit(1);
}

try {
    // Gunakan konstanta dari config/database.php
    $bootstrapDsn = "mysql:host=" . DB_HOST . ";charset=" . DB_CHARSET;
    $bootstrapPdo = new PDO($bootstrapDsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    $bootstrapPdo->exec($sql);
    fwrite(STDOUT, "Migration executed successfully: {$resolvedPath}\n");
} catch (Throwable $e) {
    fwrite(STDERR, "Migration failed: " . $e->getMessage() . "\n");
    exit(1);
}
