<?php

require 'config/database.php';

if ($pdo) {
    echo "✅ Database connected successfully";
} else {
    echo "❌ Database connection failed";
}