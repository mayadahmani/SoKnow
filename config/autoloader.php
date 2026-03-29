<?php
// config/autoloader.php

spl_autoload_register(function ($class) {
    // 1. Liste des dossiers où tu as rangé tes classes
    $sources = [
        __DIR__ . '/../classes/',
        __DIR__ . '/../views/',
        __DIR__ . '/../models/'
    ];

    // 2. On teste chaque dossier pour voir si le fichier existe
    foreach ($sources as $source) {
        $file = $source . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return; // On arrête dès qu'on a trouvé
        }
    }
});