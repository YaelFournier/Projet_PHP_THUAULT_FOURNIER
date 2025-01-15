<?php
require_once '../php_ressources/init.php';
ob_start(); // Démarre le buffer

use Quiz\Quiz;
use Quiz\Question;
use Quiz\Reponse;
use Database\DataLoaderSQLite;

// Initialisation de la base de données
$pdo = DataLoaderSQLite::getPDO();
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
            $quizId = Quiz::add($pdo, $quizName);

            foreach ($questions as $index => $question) {
                $type = $question['type'] ?? null;
                $text = $question['text'] ?? null;
                $points = $question['points'] ?? 0;
                $answers = $question['answers'] ?? [];
                $correctAnswer = $question['correctAnswer'] ?? null;

                if (empty($type) || empty($text)) {
                    $message = "La question " . ($index + 1) . " est incomplète.";
                    break;
                }

                if ($type === 'TextInput' && empty($correctAnswer)) {
                    $message = "La question " . ($index + 1) . " nécessite une réponse correcte.";
                    break;
                }

                $questionId = Question::add($pdo, $quizId, $text, $correctAnswer ?? '', $points);
                if (!$questionId) {
                    throw new Exception("Impossible d'ajouter la question : $text");
                }

                if ($type === 'Checkbox') {
                    if (empty($answers)) {
                        $message = "La question " . ($index + 1) . " nécessite des réponses pour le type 'Checkbox'.";
                        break;
                    }

                    $hasCorrectAnswer = false;

                    foreach ($answers as $answerIndex => $answer) {
                        $answerText = $answer['text'] ?? null;
                        $isCorrect = isset($answer['isCorrect']) && $answer['isCorrect'] === '1';

                        if (empty($answerText)) {
                            $message = "La réponse " . ($answerIndex + 1) . " de la question " . ($index + 1) . " est vide.";
                            break 2;
                        }

                        if ($isCorrect) {
                            $hasCorrectAnswer = true;
                        }

                        Reponse::add($pdo, $questionId, $answerText, $isCorrect);
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
        } catch (Exception $e) {
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
</head>
<body>
    <h1>Créer un Quiz</h1>
    <?php if (!empty($message)): ?>
        <div style="color: <?= strpos($message, 'succès') !== false ? 'green' : 'red'; ?>;">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>
    <form method="POST">
        <label>Nom du quiz :</label>
        <input type="text" name="quizName" required>
        <br>
        <h2>Questions</h2>
        <div id="questions-container"></div>
        <button type="button" onclick="addQuestion()">Ajouter une question</button>
        <br>
        <button type="submit">Créer le quiz</button>
    </form>
    <script>
        // Script pour ajouter dynamiquement des questions et des réponses
        let questionCount = 0;

        function addQuestion() {
            questionCount++;
            const questionContainer = document.getElementById('questions-container');
            const questionHtml = `
                <div class="question" id="question-${questionCount}">
                    <h3>Question ${questionCount}</h3>
                    <label>Type de question :</label>
                    <select name="questions[${questionCount}][type]" onchange="toggleAnswers(${questionCount}, this.value)" required>
                        <option value="TextInput">Texte</option>
                        <option value="Checkbox">Choix multiples</option>
                    </select>
                    <br>
                    <label>Texte de la question :</label>
                    <input type="text" name="questions[${questionCount}][text]" required>
                    <br>
                    <div id="text-answer-${questionCount}" style="display: block;">
                        <label>Réponse correcte :</label>
                        <input type="text" name="questions[${questionCount}][correctAnswer]" required>
                    </div>
                    <div id="answers-${questionCount}" style="display: none;">
                        <h4>Réponses possibles (pour Choix multiples) :</h4>
                        <button type="button" onclick="addAnswer(${questionCount})">Ajouter une réponse</button>
                        <div class="answers"></div>
                    </div>
                    <label>Points :</label>
                    <input type="number" name="questions[${questionCount}][points]" min="1" value="1" required>
                </div>
            `;
            questionContainer.insertAdjacentHTML('beforeend', questionHtml);
        }

        function toggleAnswers(questionId, type) {
            const answersContainer = document.getElementById(`answers-${questionId}`);
            const textAnswerContainer = document.getElementById(`text-answer-${questionId}`);
            if (type === 'Checkbox') {
                answersContainer.style.display = 'block';
                textAnswerContainer.style.display = 'none';
            } else {
                answersContainer.style.display = 'none';
                textAnswerContainer.style.display = 'block';
            }
        }

        function addAnswer(questionId) {
            const answersContainer = document.querySelector(`#question-${questionId} .answers`);
            const answerCount = answersContainer.children.length + 1;
            const answerHtml = `
                <div class="answer">
                    <label>Texte de la réponse :</label>
                    <input type="text" name="questions[${questionId}][answers][answer${answerCount}][text]" required>
                    <label>Correcte :</label>
                    <input type="checkbox" name="questions[${questionId}][answers][answer${answerCount}][isCorrect]" value="1">
                    <button type="button" onclick="removeAnswer(this)">Supprimer</button>
                </div>
            `;
            answersContainer.insertAdjacentHTML('beforeend', answerHtml);
        }

        function removeAnswer(button) {
            const answerDiv = button.parentElement;
            answerDiv.remove();
        }
    </script>
</body>
</html>