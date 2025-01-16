<?php
namespace Project\Classes\Quiz;

class Score {
    public static function affiche(\PDO $pdo) {
        // Requête SQL avec jointures pour récupérer les noms des joueurs et des quiz
        $stmt = $pdo->query('
            SELECT 
                PARTICIPER.idJ,
                JOUEUR.nomJ,
                PARTICIPER.idQi,
                QUIZ.nomQ,
                PARTICIPER.scoreMax
            FROM PARTICIPER
            INNER JOIN JOUEUR ON PARTICIPER.idJ = JOUEUR.idJ
            INNER JOIN QUIZ ON PARTICIPER.idQi = QUIZ.idQi
            ORDER BY PARTICIPER.scoreMax ASC
        ');
        
        $res = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // Générer un tableau HTML pour afficher les scores
        echo '<table>';
        echo '<tr>
                <th>Joueur</th>
                <th>Quiz</th>
                <th>Score Maximum</th>
              </tr>';

        foreach ($res as $ligne) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($ligne['nomJ']) . '</td>';
            echo '<td>' . htmlspecialchars($ligne['nomQ']) . '</td>';
            echo '<td>' . htmlspecialchars($ligne['scoreMax']) . '</td>';
            echo '</tr>';
        }

        echo '</table>';
    }
}
