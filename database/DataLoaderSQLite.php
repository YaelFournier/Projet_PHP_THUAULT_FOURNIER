<?php
namespace Database;

use PDO;
use PDOException;

class DataLoaderSQLite {
    public static function getPDO() {
        try {
            $databasePath = realpath(__DIR__ . '/BD.sqlite');
            
            if (!$databasePath) {
                throw new \Exception("Le fichier de base de données n'existe pas au chemin spécifié.");
            }
            
            $pdo = new PDO('sqlite:' . $databasePath);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e) {
            exit("Erreur de connexion : " . $e->getMessage());
        } catch (\Exception $e) {
            exit("Erreur : " . $e->getMessage());
        }
    }
}