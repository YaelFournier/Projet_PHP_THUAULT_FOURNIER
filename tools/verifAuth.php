<?php

use Project\Database\DataLoaderSQLite;
include __DIR__ . '/../php_ressources/DataLoaderSQLite.php';

$pseudo = $_POST['pseudo'];
$pdo = DataLoaderSQLite::getPDO();
try {
    $req = $pdo->prepare('SELECT nomJ FROM JOUEUR WHERE nomJ=:pseudo');
    $req->bindParam('pseudo', $pseudo);
    $req->execute();
    $res = $req->fetch();
    if ($res) { 
        header('Location: /choix_quiz');
        exit(); // Eviter l'exécution du code suivant
    } else { 
        echo '<script language="javascript">alert("pseudo inconnu")</script>';
        echo '<script language="javascript">window.location.href = "/";</script>';
        exit(); // Arrêter l'exécution du script après la redirection
    }
} catch (PDOException $e) {
    exit("Erreur de connexion : " . $e->getMessage());
} catch (Exception $e) {
    exit("Erreur : " . $e->getMessage());
}
?>
