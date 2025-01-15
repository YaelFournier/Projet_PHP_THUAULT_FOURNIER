<?php
include __DIR__ . '/../php_ressources/DataLoaderSQLite.php';

$pseudo = $_POST['pseudo'];
try {
    $req = $pdo->prepare('SELECT nomJ FROM JOUEUR WHERE nomJ=:pseudo');
    $req->bindParam('pseudo', $pseudo);
    $req->execute();
    $res = $req->fetch();
    if (!$res){
        $req2 = $pdo->prepare('INSERT INTO JOUEUR VALUES (:pseudo)');
        $req2->bindParam('pseudo', $pseudo);
        header('Location: /choix_quizz');
    }else{
        echo '<script language="javascript">alert("pseudo déjà utilisé")</script>';
    }
}catch (PDOException $e) {
    exit("Erreur de connexion : " . $e->getMessage());
} catch (Exception $e) {
    exit("Erreur : " . $e->getMessage());
}

?>