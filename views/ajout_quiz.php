<?php
namespace Project\Views;

require_once __DIR__ . '/../resources/init.php';

$pdo = \Project\Database\DataLoaderSQLite::getPDO();
$message = '';
$quizName = $_POST['quizName'] ?? '';
$questions = $_POST['questions'] ?? [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (empty($quizName)) {
        $message = "Veuillez fournir un nom pour le quiz.";
    } elseif (empty($questions)) {
        $message = "Veuillez ajouter au moins une question.";
    } else {
        try {
            $quizId = \Project\Classes\Quiz\Quiz::add($pdo, $quizName);

            foreach ($questions as $index => $question) {
                $type = $question['type'] ?? null;
                $text = $question['text'] ?? null;
                $points = $question['points'] ?? 1;

                if (empty($type) || empty($text)) {
                    $message = "La question " . ($index + 1) . " est incomplète.";
                    break;
                }

                // Pour une question de type 'TextInput', on ajoute la réponse dans l'attribut 'reponse'
                if ($type === 'TextInput') {
                    $correctAnswer = $question['correctAnswer'] ?? null;

                    // Vérifiez que la réponse correcte est fournie
                    if (empty($correctAnswer)) {
                        $message = "La question " . ($index + 1) . " de type 'TextInput' doit avoir une réponse correcte.";
                        break;
                    }

                    $questionId = \Project\Classes\Quiz\Question::add(
                        $pdo, $quizId, $text, $correctAnswer, $points
                    );
                } else {
                    // Pour les autres types de questions (ex: 'Checkbox'), on procède comme avant
                    $questionId = \Project\Classes\Quiz\Question::add(
                        $pdo, $quizId, $text, '', $points
                    );

                    $answers = $question['answers'] ?? [];
                    $hasCorrectAnswer = false;

                    foreach ($answers as $answer) {
                        $answerText = $answer['text'] ?? null;
                        $isCorrect = isset($answer['isCorrect']) && $answer['isCorrect'] === '1';

                        if (empty($answerText)) {
                            $message = "La réponse " . ($index + 1) . " est vide.";
                            break 2;
                        }

                        if ($isCorrect) {
                            $hasCorrectAnswer = true;
                        }

                        \Project\Classes\Quiz\Reponse::add($pdo, $questionId, $answerText, $isCorrect);
                    }

                    if (!$hasCorrectAnswer) {
                        $message = "La question " . ($index + 1) . " doit avoir au moins une réponse correcte.";
                        break;
                    }
                }
            }

            if (empty($message)) {
                $message = "Le quiz a été créé avec succès.";
                $quizName = '';
                $questions = [];
            }
        } catch (\Exception $e) {
            $message = "Erreur : " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un Quiz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <h1 class="text-center mb-4">Créer un Quiz</h1>
        <?php if (!empty($message)): ?>
            <div class="alert <?= strpos($message, 'succès') !== false ? 'alert-success' : 'alert-danger'; ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>
        <form method="POST" class="bg-white p-4 rounded shadow-sm">
            <div class="mb-3">
                <label class="form-label">Nom du quiz :</label>
                <input type="text" name="quizName" class="form-control" value="<?= htmlspecialchars($quizName) ?>" required>
            </div>
            <h2 class="mt-4">Questions</h2>
            <div id="questions-container" class="mb-3">
                <?php if (!empty($questions)): ?>
                    <?php foreach ($questions as $index => $question): ?>
                        <div class="border rounded p-3 mb-3" id="question-<?= $index + 1 ?>">
                            <div class="d-flex justify-content-between">
                                <h3>Question <?= $index + 1 ?></h3>
                                <button type="button" class="btn btn-danger btn-sm" onclick="removeQuestion(<?= $index + 1 ?>)">Supprimer</button>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Type de question :</label>
                                <select name="questions[<?= $index ?>][type]" class="form-select" onchange="toggleAnswers(<?= $index + 1 ?>, this.value)" required>
                                    <option value="TextInput" <?= $question['type'] === 'TextInput' ? 'selected' : '' ?>>Texte</option>
                                    <option value="Checkbox" <?= $question['type'] === 'Checkbox' ? 'selected' : '' ?>>Choix multiples</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Texte de la question :</label>
                                <input type="text" name="questions[<?= $index ?>][text]" class="form-control" value="<?= htmlspecialchars($question['text'] ?? '') ?>" required>
                            </div>
                            <div id="text-answer-<?= $index + 1 ?>" class="mb-3" style="display: <?= $question['type'] === 'TextInput' ? 'block' : 'none' ?>;">
                                <label class="form-label">Réponse correcte :</label>
                                <input type="text" name="questions[<?= $index ?>][correctAnswer]" class="form-control" value="<?= htmlspecialchars($question['correctAnswer'] ?? '') ?>" <?= $question['type'] === 'TextInput' ? 'required' : '' ?>>
                            </div>
                            <div id="answers-<?= $index + 1 ?>" style="display: <?= $question['type'] === 'Checkbox' ? 'block' : 'none' ?>;">
                                <h4>Réponses possibles (choix multiples) :</h4>
                                <button type="button" class="btn btn-outline-secondary btn-sm mb-3" onclick="addAnswer(<?= $index + 1 ?>)">Ajouter une réponse</button>
                                <div class="answers">
                                    <?php if (!empty($question['answers'])): ?>
                                        <?php foreach ($question['answers'] as $answerIndex => $answer): ?>
                                            <div class="d-flex align-items-center mb-2">
                                                <input type="text" name="questions[<?= $index ?>][answers][<?= $answerIndex ?>][text]" class="form-control me-2" placeholder="Texte de la réponse" value="<?= htmlspecialchars($answer['text'] ?? '') ?>" required>
                                                <div class="form-check me-2">
                                                    <input type="checkbox" name="questions[<?= $index ?>][answers][<?= $answerIndex ?>][isCorrect]" value="1" class="form-check-input" <?= isset($answer['isCorrect']) && $answer['isCorrect'] === '1' ? 'checked' : '' ?>>
                                                    <label class="form-check-label">Correcte</label>
                                                </div>
                                                <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeAnswer(this)">Supprimer</button>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Points :</label>
                                <input type="number" name="questions[<?= $index ?>][points]" class="form-control" min="1" value="<?= htmlspecialchars($question['points'] ?? '1') ?>" required>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <div class="d-flex justify-content-between">
                <button type="button" class="btn btn-outline-primary" onclick="addQuestion()">Ajouter une question</button>
                <button type="submit" class="btn btn-success">Créer le quiz</button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let questionCount = <?= count($questions) ?>;

        function addQuestion() {
            questionCount++;
            const questionContainer = document.getElementById('questions-container');
            const questionHtml = `
                <div class="border rounded p-3 mb-3" id="question-${questionCount}">
                    <div class="d-flex justify-content-between">
                        <h3>Question ${questionCount}</h3>
                        <button type="button" class="btn btn-danger btn-sm" onclick="removeQuestion(${questionCount})">Supprimer</button>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Type de question :</label>
                        <select name="questions[${questionCount}][type]" class="form-select" onchange="toggleAnswers(${questionCount}, this.value)" required>
                            <option value="TextInput">Texte</option>
                            <option value="Checkbox">Choix multiples</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Texte de la question :</label>
                        <input type="text" name="questions[${questionCount}][text]" class="form-control" required>
                    </div>
                    <div id="text-answer-${questionCount}" class="mb-3">
                        <label class="form-label">Réponse correcte :</label>
                        <input type="text" name="questions[${questionCount}][correctAnswer]" class="form-control" required>
                    </div>
                    <div id="answers-${questionCount}" style="display: none;">
                        <h4>Réponses possibles (choix multiples) :</h4>
                        <button type="button" class="btn btn-outline-secondary btn-sm mb-3" onclick="addAnswer(${questionCount})">Ajouter une réponse</button>
                        <div class="answers"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Points :</label>
                        <input type="number" name="questions[${questionCount}][points]" class="form-control" min="1" value="1" required>
                    </div>
                </div>
            `;
            questionContainer.insertAdjacentHTML('beforeend', questionHtml);
        }

        function removeQuestion(questionId) {
            const question = document.getElementById(`question-${questionId}`);
            question.remove();
        }

        function toggleAnswers(questionId, type) {
            const answersContainer = document.getElementById(`answers-${questionId}`);
            const textAnswerContainer = document.getElementById(`text-answer-${questionId}`);
            const textAnswerInput = document.querySelector(`input[name="questions[${questionId}][correctAnswer]"]`);

            if (type === 'Checkbox') {
                answersContainer.style.display = 'block';
                textAnswerContainer.style.display = 'none';
                textAnswerInput.removeAttribute('required');
            } else {
                answersContainer.style.display = 'none';
                textAnswerContainer.style.display = 'block';
                textAnswerInput.setAttribute('required', 'required');
            }
        }

        function addAnswer(questionId) {
            const answersContainer = document.querySelector(`#question-${questionId} .answers`);
            const answerCount = answersContainer.children.length + 1;
            const answerHtml = `
                <div class="d-flex align-items-center mb-2">
                    <input type="text" name="questions[${questionId}][answers][${answerCount}][text]" class="form-control me-2" placeholder="Texte de la réponse" required>
                    <div class="form-check me-2">
                        <input type="checkbox" name="questions[${questionId}][answers][${answerCount}][isCorrect]" value="1" class="form-check-input">
                        <label class="form-check-label">Correcte</label>
                    </div>
                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeAnswer(this)">Supprimer</button>
                </div>
            `;
            answersContainer.insertAdjacentHTML('beforeend', answerHtml);
        }

        function removeAnswer(button) {
            button.parentElement.remove();
        }
    </script>
</body>
</html>
