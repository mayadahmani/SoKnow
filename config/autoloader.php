<?php
/**
 * SoKnow Autoloader
 * Automatically loads classes from the /models folder
 */
spl_autoload_register(function ($class) {
    $directory = __DIR__ . '/../models/';
$file = $directory . $class . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});