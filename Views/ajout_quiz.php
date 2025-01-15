<?php
namespace Project\Views;

require_once __DIR__ . '/../resources/init.php';

$pdo = \Project\Database\DataLoaderSQLite::getPDO();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $quizName = $_POST['quizName'] ?? null;
    $questions = $_POST['questions'] ?? [];

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

                $questionId = \Project\Classes\Quiz\Question::add(
                    $pdo, $quizId, $text, '', $points
                );

                if ($type === 'Checkbox') {
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
    <!-- Bootstrap CSS -->
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
                <input type="text" name="quizName" class="form-control" required>
            </div>
            <h2 class="mt-4">Questions</h2>
            <div id="questions-container" class="mb-3"></div>
            <div class="d-flex justify-content-between">
                <button type="button" class="btn btn-outline-primary" onclick="addQuestion()">Ajouter une question</button>
                <button type="submit" class="btn btn-success">Créer le quiz</button>
            </div>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let questionCount = 0;

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
                textAnswerInput.removeAttribute('required'); // Supprime le "required" si Checkbox
            } else {
                answersContainer.style.display = 'none';
                textAnswerContainer.style.display = 'block';
                textAnswerInput.setAttribute('required', 'required'); // Ajoute le "required" si TextInput
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

        function validateForm(event) {
            const questionsContainer = document.getElementById('questions-container');
            const questions = questionsContainer.querySelectorAll('.question');

            for (const question of questions) {
                const type = question.querySelector('select').value;
                const text = question.querySelector('input[name*="[text]"]').value.trim();

                if (!text) {
                    alert("Une question est vide. Veuillez la remplir.");
                    event.preventDefault();
                    return false;
                }

                if (type === 'Checkbox') {
                    const answers = question.querySelectorAll('.answers .answer');
                    if (answers.length === 0) {
                        alert("Une question de type 'Checkbox' doit avoir des réponses.");
                        event.preventDefault();
                        return false;
                    }

                    const hasCorrectAnswer = Array.from(answers).some(answer => {
                        const checkbox = answer.querySelector('input[type="checkbox"]');
                        return checkbox && checkbox.checked;
                    });

                    if (!hasCorrectAnswer) {
                        alert("Une question de type 'Checkbox' doit avoir au moins une réponse correcte.");
                        event.preventDefault();
                        return false;
                    }
                }
            }
            return true;
        }

        document.querySelector('form').addEventListener('submit', validateForm);
    </script>
</body>
</html>