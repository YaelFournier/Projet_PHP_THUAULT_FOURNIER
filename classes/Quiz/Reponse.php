<?php
namespace Quiz;

class Reponse {

    public static function add(\PDO $pdo, int $idQe, string $texte, bool $correct) {
        $stmt = $pdo->prepare('
            INSERT INTO REPONSE (idQe, texte, correct)
            VALUES (:idQe, :texte, :correct)
        ');
        $stmt->execute([
            'idQe' => $idQe,
            'texte' => $texte,
            'correct' => $correct ? 1 : 0,
        ]);
    }

    public static function getByQuestion(\PDO $pdo, int $idQe) {
        $stmt = $pdo->prepare('SELECT * FROM REPONSE WHERE idQe = :idQe');
        $stmt->execute(['idQe' => $idQe]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function delete(\PDO $pdo, int $idR) {
        $stmt = $pdo->prepare('DELETE FROM REPONSE WHERE idR = :idR');
        $stmt->execute(['idR' => $idR]);
    }
}
