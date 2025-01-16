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
    $stmt = $pdo->prepare('SELECT nomJ FROM JOUEUR WHERE nomJ = :pseudo');
    $stmt->bindParam(':pseudo', $pseudo, \PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(\PDO::FETCH_ASSOC);

    if ($result) { 
        // Enregistrer l'utilisateur dans la session
        $_SESSION['user_pseudo'] = $pseudo;
        $_SESSION['message'] = 'Connexion réussie.';
        $_SESSION['message_type'] = 'success';
        header('Location: /choix_quiz');
        exit();
    } else { 
        $_SESSION['message'] = 'Pseudo inconnu.';
        $_SESSION['message_type'] = 'danger';
        header('Location: /');
        exit();
    }
} catch (\PDOException $e) {
    $_SESSION['message'] = 'Erreur de connexion : ' . $e->getMessage();
    $_SESSION['message_type'] = 'danger';
    header('Location: /');
    exit();
} catch (\Exception $e) {
    $_SESSION['message'] = 'Erreur : ' . $e->getMessage();
    $_SESSION['message_type'] = 'danger';
    header('Location: /');
    exit();
}