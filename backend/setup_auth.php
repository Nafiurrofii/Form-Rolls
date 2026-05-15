<?php

require_once __DIR__ . '/config/database.php';

try {
    echo "Creating users table...\n";
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `users` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `username` VARCHAR(50) NOT NULL UNIQUE,
            `password` VARCHAR(255) NOT NULL,
            `role` ENUM('administrator', 'operator') NOT NULL DEFAULT 'operator',
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");

    echo "Checking if users exist...\n";
    $stmt = $pdo->query("SELECT COUNT(*) FROM `users`");
    $count = $stmt->fetchColumn();

    if ($count == 0) {
        echo "Seeding default users...\n";
        
        $adminPassword = password_hash('admin123', PASSWORD_DEFAULT);
        $operatorPassword = password_hash('operator123', PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("INSERT INTO `users` (`username`, `password`, `role`) VALUES (?, ?, ?)");
        
        // Seed administrator
        $stmt->execute(['admin', $adminPassword, 'administrator']);
        
        // Seed operator
        $stmt->execute(['operator', $operatorPassword, 'operator']);

        echo "Default users seeded successfully.\n";
        echo "- admin : admin123\n";
        echo "- operator : operator123\n";
    } else {
        echo "Users table already has data. Skipping seed.\n";
    }

    echo "Setup Auth completed.\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
