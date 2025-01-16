<?php
namespace Project\Database;

use PDO;
use PDOException;

class DataLoaderSQLite {
    public static function getPDO() {
        try {
            // Chemin absolu vers la base de données
            $databasePath = realpath(__DIR__ . '/BD.sqlite');
            
            if (!$databasePath) {
                throw new \Exception("Le fichier de base de données n'existe pas au chemin spécifié.");
            }
            
            // Création de l'objet PDO
            $pdo = new PDO('sqlite:' . $databasePath, null, null, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);

            // Vérification de la connexion
            if (!$pdo) {
                throw new \Exception("Connexion à la base de données échouée.");
            }

            return $pdo;
        } catch (PDOException $e) {
            throw new \Exception("Erreur de connexion : " . $e->getMessage());
        } catch (\Exception $e) {
            throw new \Exception("Erreur : " . $e->getMessage());
        }
    }
}