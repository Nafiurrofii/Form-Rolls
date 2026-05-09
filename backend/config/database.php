<?php

// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'form_roll');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

try {

    // DSN
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;

    // PDO connection
    $pdo = new PDO($dsn, DB_USER, DB_PASS);

    // Error mode
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch mode
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $e) {

    die("Database connection failed: " . $e->getMessage());

}