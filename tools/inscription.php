<?php
include __DIR__ . '/../php_ressources/DataLoaderSQLite.php';
$pseudo = $_POST['pseudo'];
try {
    $pdo->beginTransaction();
    $req = $pdo->prepare('SELECT nomJ FROM JOUEUR WHERE nomJ=:pseudo');
    $req->bindParam(':pseudo', $pseudo);
    $req->execute();
    $res = $req->fetch();
    if ($res) {
        $pdo->rollBack();
        echo '<script language="javascript">alert("pseudo déjà utilisé")</script>';
        echo '<script language="javascript">window.location.href = "/";</script>';
        exit();
    } else {
        $req2 = $pdo->prepare('INSERT INTO JOUEUR (nomJ) VALUES (:pseudo)');
        $req2->bindParam(':pseudo', $pseudo);
        $req2->execute();
        $pdo->commit();
        echo '<script language="javascript">alert("Inscription réussie")</script>';
        echo '<script language="javascript">window.location.href = "/choix_quiz";</script>';
        exit();
    }
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    exit("Erreur : " . $e->getMessage());
}

?>