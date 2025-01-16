<?php
namespace Project\Classes\Quiz;

class Score {
    public static function affiche(\PDO $pdo) {
        $stmt = $pdo->query('
            SELECT 
                PARTICIPER.idJ,
                JOUEUR.nomJ,
                PARTICIPER.idQi,
                QUIZ.nomQ,
                PARTICIPER.scoreMax as score
            FROM PARTICIPER
            INNER JOIN JOUEUR ON PARTICIPER.idJ = JOUEUR.idJ
            INNER JOIN QUIZ ON PARTICIPER.idQi = QUIZ.idQi
            ORDER BY PARTICIPER.scoreMax ASC
        ');
        
        $res = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        echo '<table border="1">';
        echo '<tr>
                <th>Joueur</th>
                <th>Quiz</th>
                <th>Score</th>
              </tr>';

        foreach ($res as $ligne) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($ligne['nomJ']) . '</td>';
            echo '<td>' . htmlspecialchars($ligne['nomQ']) . '</td>';
            echo '<td>' . htmlspecialchars($ligne['score']) . '</td>';
            echo '</tr>';
        }

        echo '</table>';
    }
}
