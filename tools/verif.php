<?php 
namespace Project\Tools;

require_once __DIR__ .'/../resources/init.php';

$idJ = $_SESSION['user_id'];
$idQi = $_SESSION['quiz_id'];

use Project\Database\DataLoaderSQLite;
use Project\Classes\Quiz\Question;

$pdo = DataLoaderSQLite::getPDO();

$tout_bon=true;
$score = 0;
foreach ($_POST as $key => $value){
    if (Question::verifyAnswer($pdo, $key, $value)){
        $score += Question::getNbPoints($pdo, $key);
    }
}

$req = $pdo->prepare('INSERT INTO PARTICIPER VALUES (:idJ, :idQi, :score)');
$req->execute([
    ":idJ" => $idJ,
    ":idQi" => $idQi,
    ":score" => $score
]);
header('Location: /tableau_score')



?>