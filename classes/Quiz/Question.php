<?php
namespace Quiz;

class Question
{
    public static function add(\PDO $pdo, int $idQi, string $texte, string $reponse, int $nbPoints) {
        $stmt = $pdo->prepare('
            INSERT INTO QUESTION (idQe, texte, reponse, idQi, nbPoints)
            VALUES ((SELECT IFNULL(MAX(idQe), 0) + 1 FROM QUESTION WHERE idQi = :idQi), :texte, :reponse, :idQi, :nbPoints)
        ');
        $stmt->execute([
            'idQi' => $idQi,
            'texte' => $texte,
            'reponse' => $reponse,
            'nbPoints' => $nbPoints,
        ]);
    }

    public static function getByQuiz(\PDO $pdo, int $idQi) {
        $stmt = $pdo->prepare('SELECT * FROM QUESTION WHERE idQi = :idQi ORDER BY idQe ASC');
        $stmt->execute(['idQi' => $idQi]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function delete(\PDO $pdo, int $idQe, int $idQi) {
        $stmt = $pdo->prepare('DELETE FROM QUESTION WHERE idQe = :idQe AND idQi = :idQi');
        $stmt->execute(['idQe' => $idQe, 'idQi' => $idQi]);
    }

    public static function verify(\PDO $pdo, int $idQe, string $reponse) {
        $stmt = $pdo->prepare('SELECT reponse FROM QUESTION WHERE idQe = :idQe');
        $stmt->execute(['idQe' => $idQe]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result['reponse'] === $reponse;
    }
}
