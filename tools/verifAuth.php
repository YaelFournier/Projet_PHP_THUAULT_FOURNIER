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

// Récupérer et nettoyer le pseudo
$pseudo = trim($_POST['pseudo'] ?? '');

if (empty($pseudo)) {
    $_SESSION['message'] = 'Le pseudo est requis.';
    $_SESSION['message_type'] = 'warning';
    header('Location: /');
    exit();
}

$pdo = DataLoaderSQLite::getPDO();

try {
    // Utiliser la méthode getByPseudo pour récupérer le joueur
    $joueur = Joueur::getByPseudo($pdo, $pseudo);

    if ($joueur) { 
        // Enregistrer l'utilisateur dans la session
        $_SESSION['user_pseudo'] = $joueur['nomJ'];
        $_SESSION['user_id'] = $joueur['idJ'];
        $_SESSION['message'] = 'Connexion réussie.';
        $_SESSION['message_type'] = 'success';
        header('Location: choix_quiz');
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
?>