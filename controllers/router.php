<?php

namespace src\controllers;

class Router
{
    public function handleRequest() 
    {
        $data = $_SERVER['REQUEST_URI'];

        switch ($data) {
            case '/':
                require_once VIEWS_PATH . '/home.php';
                break;
            default:
                header("Location: /");
                exit;
        }
    }
}