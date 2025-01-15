<?php

namespace controllers;

class Router
{
    public function handleRequest() 
    {
        $data = $_SERVER['REQUEST_URI'];

        switch ($data) {
            case '/':
                require_once VIEWS_PATH . '/home.php';
                break;
            case '/quizz':
                require_once VIEWS_PATH . '/Template.php';
                break;
                
            case '/verif':
                require_once TOOL_PATH . '/verif.php';
                break;

            default:
                header("Location: /");
                exit;
        }
    }
}