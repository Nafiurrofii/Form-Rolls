<?php

class User {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function findByUsername($username) {
        $stmt = $this->pdo->prepare("SELECT * FROM `users` WHERE `username` = ? LIMIT 1");
        $stmt->execute([$username]);
        return $stmt->fetch();
    }
}
