<?php
require_once __DIR__ . '/../resources/init.php';

$pdo = \Project\Database\DataLoaderSQLite::getPDO();
$idQuiz = 11; // changer en fonction du quiz choisi par l'utilisateur
$req = $pdo->prepare('SELECT * FROM QUESTION WHERE idQi=:id;');
$req->bindParam(':id', $idQuiz);
$req->execute();
$res= $req->fetchAll(PDO::FETCH_ASSOC);
print_r($res);

?>
<html>
    <head>
        <meta charset="utf-8">
        <title>Quiz</title>
    </head>
    <body>
        <form method="POST" action="verif">
        <?php 
        
        Project\Classes\Quiz\Quiz::afficheQuiz($pdo, $idQuiz);
        
        ?>
        <input type="submit" value="Valider les réponses">
        </form>
    </body>
</html>