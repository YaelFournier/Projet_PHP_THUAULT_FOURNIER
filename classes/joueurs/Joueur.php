<?php
namespace Project\Classes\Joueurs;

class Joueur {

    public static function createAnonyme(\PDO $pdo): int {
        try {
            // Générer un nom unique pour le joueur anonyme
            $stmt = $pdo->query("SELECT COUNT(*) AS count FROM JOUEUR WHERE nomJ LIKE 'anonyme_%'");
            $result = $stmt->fetch();
            $count = $result['count'] ?? 0;
            $username = "anonyme_" . ($count + 1);

            // Insérer le nouveau joueur anonyme
            $stmt = $pdo->prepare('INSERT INTO JOUEUR (nomJ) VALUES (:nomJ)');
            $stmt->execute(['nomJ' => $username]);

            // Retourner l'ID du joueur créé
            return (int)$pdo->lastInsertId();
        } catch (\PDOException $e) {
            throw new \Exception("Erreur lors de la création d'un joueur anonyme : " . $e->getMessage());
        }
    }

    public static function getById(\PDO $pdo, int $idJ): ?array {
        $stmt = $pdo->prepare('SELECT * FROM JOUEUR WHERE idJ = :idJ');
        $stmt->execute(['idJ' => $idJ]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result !== false ? $result : null;
    }

    public static function getByPseudo(\PDO $pdo, string $pseudo): ?array {
        $stmt = $pdo->prepare('SELECT * FROM JOUEUR WHERE nomJ = :pseudo');
        $stmt->execute(['pseudo' => $pseudo]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result !== false ? $result : null;
    }

    public static function getAll(\PDO $pdo): array {
        $stmt = $pdo->query('SELECT * FROM JOUEUR');
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function create(\PDO $pdo, string $pseudo): int {
        try {
            // Insérer un nouveau joueur
            $stmt = $pdo->prepare('INSERT INTO JOUEUR (nomJ) VALUES (:pseudo)');
            $stmt->execute(['pseudo' => $pseudo]);

            // Retourner l'ID du joueur créé
            return (int)$pdo->lastInsertId();
        } catch (\PDOException $e) {
            throw new \Exception("Erreur lors de la création d'un joueur : " . $e->getMessage());
        }
    }

    public static function updateName(\PDO $pdo, int $idJ, string $newName): bool {
        $stmt = $pdo->prepare('UPDATE JOUEUR SET nomJ = :newName WHERE idJ = :idJ');
        return $stmt->execute(['idJ' => $idJ, 'newName' => $newName]);
    }

    public static function deleteById(\PDO $pdo, int $idJ): bool {
        $stmt = $pdo->prepare('DELETE FROM JOUEUR WHERE idJ = :idJ');
        return $stmt->execute(['idJ' => $idJ]);
    }
}