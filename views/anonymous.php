<?php
namespace Project\Views;

require_once __DIR__ . '/../resources/init.php';

use Project\Classes\Joueurs\Joueur;
use Project\Database\DataLoaderSQLite;

// Vérifier que la requête est bien en POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['message'] = 'Méthode non autorisée.';
    $_SESSION['message_type'] = 'danger';
    header('Location: /');
    exit();
}

$pdo = DataLoaderSQLite::getPDO();

try {
    // Créer un joueur anonyme
    $newId = Joueur::createAnonyme($pdo);

    // Récupérer les informations du joueur créé
    $joueur = Joueur::getById($pdo, $newId);
    if (!$joueur) {
        throw new \Exception("Erreur lors de la création du joueur anonyme.");
    }

    // Enregistrer l'utilisateur dans la session
    $_SESSION['user_pseudo'] = $joueur['nomJ'];
    $_SESSION['user_id'] = $joueur['idJ'];
    $_SESSION['message'] = 'Connexion anonyme réussie.';
    $_SESSION['message_type'] = 'success';
    header('Location: choix_quiz');
    exit();
} catch (\Exception $e) {
    $_SESSION['message'] = 'Erreur : ' . $e->getMessage();
    $_SESSION['message_type'] = 'danger';
    header('Location: /');
    exit();
}
?>