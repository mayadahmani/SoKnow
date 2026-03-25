<?php

spl_autoload_register(function ($class) {
    $directory = __DIR__ . '/../models/';
$file = $directory . $class . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});