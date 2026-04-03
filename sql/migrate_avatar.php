<?php
require_once __DIR__ . '/../config/database.php';

try {
    $pdo->exec("ALTER TABLE users ADD COLUMN avatar_url VARCHAR(500) DEFAULT NULL AFTER preferred_lang, ADD COLUMN banner_url VARCHAR(500) DEFAULT NULL AFTER avatar_url");
    echo "Columns avatar_url and banner_url added successfully.\n";
} catch (PDOException $e) {
    if (str_contains($e->getMessage(), 'Duplicate column')) {
        echo "Columns already exist, nothing to do.\n";
    } else {
        echo "Error: " . $e->getMessage() . "\n";
    }
}
