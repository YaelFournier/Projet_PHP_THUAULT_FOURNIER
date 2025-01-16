<?php
namespace Project\Classes\Quiz;

class Question {

    public static function add(\PDO $pdo, int $idQi, string $texte, string $reponse, int $nbPoints) {
        $stmt = $pdo->prepare('
            INSERT INTO QUESTION (texte, reponse, idQi, nbPoints)
            VALUES (:texte, :reponse, :idQi, :nbPoints)
        ');
        $stmt->execute([
            'texte' => $texte,
            'reponse' => $reponse,
            'idQi' => $idQi,
            'nbPoints' => $nbPoints,
        ]);
        return $pdo->lastInsertId(); // Retourne l'identifiant de la question
    }    

    public static function getByQuiz(\PDO $pdo, int $idQi) {
        $stmt = $pdo->prepare('SELECT * FROM QUESTION WHERE idQi = :idQi ORDER BY idQe ASC');
        $stmt->execute(['idQi' => $idQi]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function getById(\PDO $pdo, int $idQe) {
        $stmt = $pdo->prepare('SELECT * FROM QUESTION WHERE idQe = :idQe');
        $stmt->execute(['idQe' => $idQe]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public static function update(\PDO $pdo, int $idQe, string $texte, string $reponse, int $nbPoints) {
        $stmt = $pdo->prepare('
            UPDATE QUESTION
            SET texte = :texte, reponse = :reponse, nbPoints = :nbPoints
            WHERE idQe = :idQe
        ');
        $stmt->execute([
            'idQe' => $idQe,
            'texte' => $texte,
            'reponse' => $reponse,
            'nbPoints' => $nbPoints,
        ]);
    }

    public static function delete(\PDO $pdo, int $idQe) {
        $stmt = $pdo->prepare('DELETE FROM QUESTION WHERE idQe = :idQe');
        $stmt->execute(['idQe' => $idQe]);
    }

    public static function verifyAnswer(\PDO $pdo, int $idQe, string $userAnswer) {
        $stmt = $pdo->prepare('SELECT reponse FROM QUESTION WHERE idQe = :idQe');
        $stmt->execute(['idQe' => $idQe]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result && $result['reponse'] === $userAnswer;
    }

    public static function afficheQuestion(\PDO $pdo, int $idQe){
        $question = Question::getById($pdo, $idQe);
        $reponses = Reponse::getByQuestion($pdo, $idQe);
        echo '<li>';
        echo '<p>'.$question['texte'].'</p>';
        foreach ($reponses as $r){
            $idR= $r['idR'];
            echo '<ul>';
            Reponse::afficheReponse($pdo, $idR);
            echo '</ul>';
        }
        echo '</li>';
    }
}
