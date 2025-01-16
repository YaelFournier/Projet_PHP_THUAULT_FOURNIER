<?php
require_once __DIR__ . '/resources/autoloader.php';

use Project\Database\DataLoaderSQLite;

try {
    $pdo = DataLoaderSQLite::getPDO();
} catch (\Exception $e) {
    echo "Erreur : " . $e->getMessage();
}

use Project\Controllers\Router;

Router::handleRequest();
