<?php
namespace Project\Resources;

spl_autoload_register(function ($className) {
    // Convertir le namespace en chemin de fichier
    // Supprimer le préfixe 'Project\' du namespace
    $relativeClass = str_replace('Project\\', '', $className);
    
    // Chemin de base du projet
    $baseDir = realpath(__DIR__ . '/../'); // Le répertoire racine du projet
    
    // Remplacer les séparateurs de namespace par des séparateurs de répertoires
    $classPath = str_replace('\\', DIRECTORY_SEPARATOR, $relativeClass);
    
    // Découper le chemin en segments
    $classPathParts = explode(DIRECTORY_SEPARATOR, $classPath);
    
    // Séparer le nom de la classe du chemin des répertoires
    $classNamePart = array_pop($classPathParts); // Nom de la classe
    $directoryParts = $classPathParts;
    
    // Convertir les segments de répertoire en minuscules
    foreach ($directoryParts as &$part) {
        $part = strtolower($part);
    }
    unset($part); // Détacher la référence
    
    // Reconstituer le chemin des répertoires en minuscules
    $directoryPath = implode(DIRECTORY_SEPARATOR, $directoryParts);
    
    // Reconstituer le chemin complet avec le nom de la classe
    if (!empty($directoryPath)) {
        $file = $baseDir . DIRECTORY_SEPARATOR . $directoryPath . DIRECTORY_SEPARATOR . $classNamePart . '.php';
    } else {
        // Si la classe est dans le namespace racine 'Project'
        $file = $baseDir . DIRECTORY_SEPARATOR . $classNamePart . '.php';
    }
    
    // Inclure le fichier ou afficher un message d'erreur
    if (file_exists($file)) {
        require_once $file;
    } else {
        // Utiliser un mécanisme d'erreur approprié
        // Par exemple, lancer une exception
        throw new \Exception("Erreur : Impossible de charger la classe {$className} (chemin : {$file})");
        
        // Ou utiliser trigger_error
        // trigger_error("Erreur : Impossible de charger la classe {$className} (chemin : {$file})", E_USER_WARNING);
    }
});