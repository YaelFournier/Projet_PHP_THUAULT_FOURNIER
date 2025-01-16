<?php
namespace Project\Views;

require_once __DIR__ . '/../resources/init.php';

// Récupérer et nettoyer les messages de la session
$message = $_SESSION['message'] ?? '';
$message_type = $_SESSION['message_type'] ?? 'info';
unset($_SESSION['message'], $_SESSION['message_type']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Connexion</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Optionnel : Centrer verticalement */
        .full-height {
            height: 100vh;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container d-flex justify-content-center align-items-center full-height">
        <div class="row w-100">
            <!-- Formulaire de Connexion -->
            <div class="col-md-6">
                <div class="card mb-4 shadow-sm">
                    <div class="card-header text-center">
                        Connexion
                    </div>
                    <div class="card-body">
                        <?php if (!empty($message) && $message_type === 'danger'): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?= htmlspecialchars($message) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>
                        <form method="POST" action="verifAuth">
                            <div class="mb-3">
                                <label for="login_pseudo" class="form-label">Pseudo :</label>
                                <input type="text" id="login_pseudo" name="pseudo" class="form-control" placeholder="Entrez votre pseudo" required>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">S'identifier</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Formulaire d'Inscription -->
            <div class="col-md-6">
                <div class="card mb-4 shadow-sm">
                    <div class="card-header text-center">
                        Inscription
                    </div>
                    <div class="card-body">
                        <?php if (!empty($message) && $message_type === 'success'): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <?= htmlspecialchars($message) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php elseif (!empty($message) && $message_type === 'warning'): ?>
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <?= htmlspecialchars($message) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>
                        <form method="POST" action="inscription">
                            <div class="mb-3">
                                <label for="register_pseudo" class="form-label">Pseudo :</label>
                                <input type="text" id="register_pseudo" name="pseudo" class="form-control" placeholder="Entrez votre pseudo" required>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-success">S'inscrire</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS (inclut Popper.js) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Optionnel : Pour gérer les alertes automatiques -->
    <script>
        // Fermer automatiquement les alertes après 5 secondes
        setTimeout(() => {
            const alert = document.querySelector('.alert');
            if (alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        }, 5000);
    </script>
</body>
</html>