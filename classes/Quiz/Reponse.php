<?php

namespace Quiz;

class Question
{
    public static function add(\PDO $pdo, int $idQe, string $texte, string $reponse, int $idQi, int $nbPoints) {
        $stmt = $pdo->prepare('
            INSERT INTO QUESTION (idQe, texte, reponse, idQi, nbPoints)
            VALUES (:idQe, :texte, :reponse, :idQi, :nbPoints)
        ');
        $stmt->execute([
            'idQe' => $idQe,
            'texte' => $texte,
            'reponse' => $reponse,
            'idQi' => $idQi,
            'nbPoints' => $nbPoints,
        ]);
    }

    public static function getByQuiz(\PDO $pdo, int $idQi) {
        $stmt = $pdo->prepare('SELECT * FROM QUESTION WHERE idQi = :idQi');
        $stmt->execute(['idQi' => $idQi]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function getById(\PDO $pdo, int $idQe, int $idQi) {
        $stmt = $pdo->prepare('SELECT * FROM QUESTION WHERE idQe = :idQe AND idQi = :idQi');
        $stmt->execute(['idQe' => $idQe, 'idQi' => $idQi]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public static function update(\PDO $pdo, int $idQe, int $idQi, string $texte, string $reponse, int $nbPoints) {
        $stmt = $pdo->prepare('
            UPDATE QUESTION
            SET texte = :texte, reponse = :reponse, nbPoints = :nbPoints
            WHERE idQe = :idQe AND idQi = :idQi
        ');
        $stmt->execute([
            'idQe' => $idQe,
            'idQi' => $idQi,
            'texte' => $texte,
            'reponse' => $reponse,
            'nbPoints' => $nbPoints,
        ]);
    }

    public static function delete(\PDO $pdo, int $idQe, int $idQi) {
        $stmt = $pdo->prepare('DELETE FROM QUESTION WHERE idQe = :idQe AND idQi = :idQi');
        $stmt->execute(['idQe' => $idQe, 'idQi' => $idQi]);
    }
}
