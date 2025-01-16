<?php
namespace Project\Views;

require_once __DIR__ . '/../resources/init.php';

use Project\Database\DataLoaderSQLite;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['message'] = 'Méthode non autorisée.';
    $_SESSION['message_type'] = 'danger';
    header('Location: home.php');
    exit();
}

$pseudo = trim($_POST['pseudo'] ?? '');

if (empty($pseudo)) {
    $_SESSION['message'] = 'Le pseudo est requis.';
    $_SESSION['message_type'] = 'warning';
    header('Location: home.php');
    exit();
}

$pdo = DataLoaderSQLite::getPDO();

try {
    $pdo->beginTransaction();

    // Vérifier si le pseudo existe déjà
    $stmt = $pdo->prepare('SELECT nomJ FROM JOUEUR WHERE nomJ = :pseudo');
    $stmt->bindParam(':pseudo', $pseudo, \PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(\PDO::FETCH_ASSOC);

    if ($result) {
        $pdo->rollBack();
        $_SESSION['message'] = 'Pseudo déjà utilisé.';
        $_SESSION['message_type'] = 'danger';
        header('Location: home.php');
        exit();
    }

    // Insérer le nouveau joueur
    $stmt = $pdo->prepare('INSERT INTO JOUEUR (nomJ) VALUES (:pseudo)');
    $stmt->bindParam(':pseudo', $pseudo, \PDO::PARAM_STR);
    $stmt->execute();
    $pdo->commit();

    // Enregistrer l'utilisateur dans la session
    $_SESSION['user_pseudo'] = $pseudo;
    $_SESSION['message'] = 'Inscription réussie.';
    $_SESSION['message_type'] = 'success';
    header('Location: /choix_quiz');
    exit();
} catch (\PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    $_SESSION['message'] = 'Erreur lors de l\'inscription : ' . $e->getMessage();
    $_SESSION['message_type'] = 'danger';
    header('Location: home.php');
    exit();
} catch (\Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    $_SESSION['message'] = 'Erreur : ' . $e->getMessage();
    $_SESSION['message_type'] = 'danger';
    header('Location: home.php');
    exit();
}