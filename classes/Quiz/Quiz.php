<?php
namespace Quiz;

class Quiz
{
    // Récupérer un quiz par ID
    public static function get($pdo, $idQuiz) {
        $stmt = $pdo->prepare('SELECT * FROM QUIZ WHERE idQi = :idQuiz');
        $stmt->execute(['idQuiz' => $idQuiz]);
        return $stmt->fetch();
    }

    // Récupérer tous les quiz
    public static function getAll($pdo) {
        $stmt = $pdo->query('SELECT * FROM QUIZ');
        return $stmt->fetchAll();
    }

    // Insérer un nouveau quiz
    public static function insert($pdo, $nomQ) {
        $stmt = $pdo->prepare('INSERT INTO QUIZ (nomQ) VALUES (:nomQ)');
        $stmt->execute(['nomQ' => $nomQ]);
        return $pdo->lastInsertId();
    }

    // Mettre à jour un quiz existant
    public static function update($pdo, $idQuiz, $nomQ) {
        $stmt = $pdo->prepare('UPDATE QUIZ SET nomQ = :nomQ WHERE idQi = :idQuiz');
        $stmt->execute(['idQuiz' => $idQuiz, 'nomQ' => $nomQ]);
    }

    // Supprimer un quiz par ID
    public static function delete($pdo, $idQuiz) {
        $stmt = $pdo->prepare('DELETE FROM QUIZ WHERE idQi = :idQuiz');
        $stmt->execute(['idQuiz' => $idQuiz]);
    }

    public static function getQuestions($pdo, $idQuiz) {
        $stmt = $pdo->prepare('SELECT * FROM QUESTION WHERE idQi = :idQuiz');
        $stmt->execute(['idQuiz' => $idQuiz]);
        return $stmt->fetchAll();
    }

    public static function getPoints($pdo, $idQuiz, $idJ) {
        $stmt = $pdo->prepare('SELECT SUM(points) AS totalPoints FROM REPONSE WHERE idQi = :idQuiz AND idJ = :idJ');
        $stmt->execute(['idQuiz' => $idQuiz, 'idJ' => $idJ]);
        return $stmt->fetch();
    }
}
?>