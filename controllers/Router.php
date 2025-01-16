<?php
namespace Project\Controllers;

class Router
{
    public function handleRequest()
    {
        require_once __DIR__ . '/../config/config.php';

        $requestUri = $_SERVER['REQUEST_URI'];

        $routes = [
            '/' => VIEWS_PATH . '/home.php',
            '/quizz' => VIEWS_PATH . '/Template.php',
            '/verif' => TOOL_PATH . '/verif.php',
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
