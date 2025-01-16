<?php
namespace Project\Classes\Quiz;

class Reponse {

    public static function add(\PDO $pdo, int $idQe, string $texte, bool $correct) {
        try {
            $stmt = $pdo->prepare('
                INSERT INTO REPONSE (idQe, texte, correct)
                VALUES (:idQe, :texte, :correct)
            ');
            $stmt->execute([
                'idQe' => $idQe,
                'texte' => $texte,
                'correct' => $correct ? 1 : 0,
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
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

    public static function getById(\PDO $pdo, int $idR){
        $stmt = $pdo->prepare('SELECT * FROM REPONSE WHERE idR = :idR');
        $stmt->execute(['idR' => $idR]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public static function afficheReponse(\PDO $pdo, int $idR){
        $reponse = Reponse::getById($pdo, $idR);
        echo '<li>';
        echo '<label for="'.$idR.'">'. $reponse['texte'] .'</label>';
        echo '<input type="checkbox" id="'.$idR.'" name="'.$idR.'">';
        echo '</li>';
    }
}
