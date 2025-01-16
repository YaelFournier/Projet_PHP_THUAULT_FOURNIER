<?php
namespace Project\Views;

require_once __DIR__ . '/../resources/init.php';

use Project\Classes\Quiz\Quiz;

// Initialisation de la connexion PDO
$pdo = \Project\Database\DataLoaderSQLite::getPDO();

// Récupération de tous les quiz
$quizzes = Quiz::getAll($pdo);

// Message éventuel (par exemple, après une action)
$message = $_GET['message'] ?? '';

// Gestion de la soumission du formulaire pour sélectionner un quiz
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['select_quiz'])) {
    $selectedQuizId = (int)$_POST['quiz_id'];

    // Vérifier que le quiz existe
    $quiz = Quiz::getById($pdo, $selectedQuizId);
    if ($quiz) {
        // Enregistrer l'ID du quiz dans la session
        $_SESSION['quiz_id'] = $selectedQuizId;

        // Rediriger vers la page de prise du quiz
        header('Location: page_quiz.php');
        exit;
    } else {
        $message = "Quiz invalide sélectionné.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choisir un Quiz</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Optionnel : Ajouter un style personnalisé pour limiter la largeur maximale */
        .main-content {
            max-width: 1200px; /* Ajustez selon vos besoins */
            margin: 0 auto;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container py-5 main-content">
        <h1 class="text-center mb-4">Choisir un Quiz</h1>

        <!-- Affichage des messages -->
        <?php if (!empty($message)): ?>
            <div class="alert alert-info">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($quizzes)): ?>
            <div class="row justify-content-center">
                <?php foreach ($quizzes as $quiz): ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title"><?= htmlspecialchars($quiz['nomQ']) ?></h5>
                                <p class="card-text">ID du Quiz : <?= htmlspecialchars($quiz['idQi']) ?></p>
                                <form method="POST" class="mt-auto">
                                    <input type="hidden" name="quiz_id" value="<?= htmlspecialchars($quiz['idQi']) ?>">
                                    <button type="submit" name="select_quiz" class="btn btn-primary">
                                        Démarrer
                                    </button>
                                    <a href="details_quizz.php?id=<?= urlencode($quiz['idQi']) ?>" class="btn btn-secondary mt-2">
                                        Détails
                                    </a>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-warning text-center">
                Aucun quiz disponible pour le moment. <a href="ajout" class="alert-link">Créer un nouveau quiz</a>.
            </div>
        <?php endif; ?>

        <!-- Optionnel : Bouton pour créer un nouveau quiz -->
        <div class="text-center mt-4">
            <a href="ajout" class="btn btn-success">Créer un Nouveau Quiz</a>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>