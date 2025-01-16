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
    $pdo->beginTransaction();

    // Vérifier si le pseudo existe déjà
    $joueur = Joueur::getByPseudo($pdo, $pseudo);

    if ($joueur) {
        $pdo->rollBack();
        $_SESSION['message'] = 'Pseudo déjà utilisé.';
        $_SESSION['message_type'] = 'danger';
        header('Location: /');
        exit();
    }

    // Créer le nouveau joueur
    $newId = Joueur::create($pdo, $pseudo);
    $pdo->commit();

    // Enregistrer l'utilisateur dans la session
    $_SESSION['user_pseudo'] = $pseudo;
    $_SESSION['user_id'] = $newId;
    $_SESSION['message'] = 'Inscription réussie.';
    $_SESSION['message_type'] = 'success';
    header('Location: choix_quiz');
    exit();
} catch (\PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    $_SESSION['message'] = 'Erreur lors de l\'inscription : ' . $e->getMessage();
    $_SESSION['message_type'] = 'danger';
    header('Location: /');
    exit();
} catch (\Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    $_SESSION['message'] = 'Erreur : ' . $e->getMessage();
    $_SESSION['message_type'] = 'danger';
    header('Location: /');
    exit();
}
?>