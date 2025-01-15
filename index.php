<?php
require_once __DIR__ . '/resources/autoloader.php';

use Project\Database\DataLoaderSQLite;

try {
    $pdo = DataLoaderSQLite::getPDO();
    echo "Autoloader opérationnel. Connexion réussie à la base de données.";
} catch (\Exception $e) {
    echo "Erreur : " . $e->getMessage();
}