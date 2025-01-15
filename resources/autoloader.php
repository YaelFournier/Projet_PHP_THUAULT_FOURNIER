<?php
namespace Project\Resources;

spl_autoload_register(function ($className) {
    // Convertir le namespace en chemin de fichier
    $classPath = str_replace(['Project\\', '\\'], ['', DIRECTORY_SEPARATOR], $className);

    // Chemin de base du projet
    $baseDir = realpath(__DIR__ . '/../'); // Le répertoire racine du projet

    // Générer le chemin complet du fichier
    $file = $baseDir . DIRECTORY_SEPARATOR . $classPath . '.php';

    // Inclure le fichier ou afficher un message d'erreur
    if (file_exists($file)) {
        require_once $file;
    } else {
        echo "Autoloading : Impossible de charger la classe {$className} (chemin : {$file})<br>";
    }
});
