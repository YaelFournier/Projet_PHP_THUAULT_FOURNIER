<?php
namespace Joueurs;

Class Joueur{
    
    public static function createAnonyme(\PDO $pdo) {
        try {
            $stmt = $pdo->query('SELECT MAX(idJ) AS lastId FROM JOUEUR');
            $result = $stmt->fetch();
            $lastId = $result['lastId'] ?? 0;

            $newId = $lastId + 1;

            $username = "anonyme_{$newId}";

            $stmt = $pdo->prepare('INSERT INTO JOUEUR (idJ, nomJ) VALUES (:idJ, :nomJ)');
            $stmt->execute([
                'idJ' => $newId,
                'nomJ' => $username,
            ]);
            return $newId;
        } catch (\PDOException $e) {
            throw new \Exception("Erreur lors de la création d'un joueur anonyme : " . $e->getMessage());
        }
    }

    public static function getById(\PDO $pdo, int $idJ) {
        $stmt = $pdo->prepare('SELECT * FROM JOUEUR WHERE idJ = :idJ');
        $stmt->execute(['idJ' => $idJ]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public static function getAll(\PDO $pdo) {
        $stmt = $pdo->query('SELECT * FROM JOUEUR');
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function updateName(\PDO $pdo, int $idJ, string $newName) {
        $stmt = $pdo->prepare('UPDATE JOUEUR SET nomJ = :newName WHERE idJ = :idJ');
        return $stmt->execute(['idJ' => $idJ, 'newName' => $newName]);
    }

    public static function deleteById(\PDO $pdo, int $idJ) {
        $stmt = $pdo->prepare('DELETE FROM JOUEUR WHERE idJ = :idJ');
        return $stmt->execute(['idJ' => $idJ]);
    }
}