<?php
namespace Project\Controllers;

class Router
{
    public static function handleRequest()
    {
        require_once __DIR__ . '/config.php';

        $requestUri = $_SERVER['REQUEST_URI'];

        $routes = [
            '/' => VIEWS_PATH . '/home.php',
            '/quizz' => VIEWS_PATH . '/page_quiz.php',
            '/verif' => TOOL_PATH . '/verif.php',
            '/ajout' => VIEWS_PATH . '/ajout_quiz.php',
            '/choix_quiz' => VIEWS_PATH . '/choix_quizz.php',
            '/verifAuth' => TOOL_PATH . '/verifAuth.php', 
            '/inscription' => TOOL_PATH . '/inscription.php',
            '/tableau_score' => VIEWS_PATH . '/tableau_score.php'
        ];

        if (array_key_exists($requestUri, $routes)) {
            $file = $routes[$requestUri];
            if (file_exists($file)) {
                require_once $file;
            } else {
                http_response_code(404);
                echo "Error 404: File not found.";
            }
        } else {
            header("Location: /");
            exit;
        }
    }
}
