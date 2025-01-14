<?php

?>

<html>
    <head>
        <title>
            Quizz
        </title>
    </head>
    <body>
        <?php
        use Tools\type\Checkbox;
        
        $question = [array(
            "name" => "test",
            "type" => "checkbox",
            "text" => "quelles sont les bonnes réponses ?",
            "choices" => [
                array(
                    "text" => "ça",
                    "value" => "oui"
                ),
                array(
                    "text"=> "pas ça",
                    "value"=> "non"
                ),
                array(
                    "text"=> "ça",
                    "value"=> "oui"
                )
            ],
            "answer" => ["oui","oui"],
            "score" => 1
        )];

        $question_handler = array(
            "checkbox" => function($q){return new Checkbox($q);}
        );

        if ($_SERVER['REQUEST_METHOD'] == "GET"){
            echo "<form method='POST' action='verif'><ul>";
            foreach ($question as $q){
                echo "<li>";
                $elem = $question_handler[$q["type"]]($q);
                echo $elem -> question($q);
            }
            echo '</ul>';
            echo '<input type="submit" value="Valider">';
        }
        

        ?>
    </body>
</html>