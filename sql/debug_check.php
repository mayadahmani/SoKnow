<?php
require_once __DIR__ . '/../config/database.php';
$r = $pdo->query('SELECT id, avatar_url, banner_url FROM users');
print_r($r->fetchAll(PDO::FETCH_ASSOC));
