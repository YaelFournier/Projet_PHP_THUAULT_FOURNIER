<?php
namespace Project\Classes\Quiz;

class Quiz {

    public static function getById(\PDO $pdo, int $idQuiz) {
        $stmt = $pdo->prepare('SELECT * FROM QUIZ WHERE idQi = :idQuiz');
        $stmt->execute(['idQuiz' => $idQuiz]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public static function getAll(\PDO $pdo) {
        $stmt = $pdo->query('SELECT * FROM QUIZ');
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function add(\PDO $pdo, string $nomQ) {
        $stmt = $pdo->prepare('INSERT INTO QUIZ (nomQ) VALUES (:nomQ)');
        $stmt->execute(['nomQ' => $nomQ]);
        return $pdo->lastInsertId();
    }

    public static function update(\PDO $pdo, int $idQuiz, string $nomQ) {
        $stmt = $pdo->prepare('UPDATE QUIZ SET nomQ = :nomQ WHERE idQi = :idQuiz');
        $stmt->execute(['idQuiz' => $idQuiz, 'nomQ' => $nomQ]);
    }

    public static function delete(\PDO $pdo, int $idQuiz) {
        $stmt = $pdo->prepare('DELETE FROM QUIZ WHERE idQi = :idQuiz');
        $stmt->execute(['idQuiz' => $idQuiz]);
    }

    public static function getQuestions(\PDO $pdo, int $idQuiz) {
        return Question::getByQuiz($pdo, $idQuiz);
    }

    public static function afficheQuiz(\PDO $pdo, int $idQuiz): void {
        foreach (Quiz::getQuestions($pdo, $idQuiz) as $question){
            $idQe = $question['idQe'];
            echo '<ul>';
            Question::afficheQuestion($pdo, $idQe);
            echo '</ul>';
        }
    }
}