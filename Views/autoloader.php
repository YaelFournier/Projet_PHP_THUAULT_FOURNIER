<?php

spl_autoload_register(function ($className) {
    $className = str_replace('\\', '/', $className);

    $file = __DIR__ . "/../classes/" . $className . '.php';
    echo $file;

    if (file_exists($file)) {
        require_once  $file;
    }
});
