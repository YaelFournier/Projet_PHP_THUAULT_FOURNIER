<?php

require_once __DIR__ . '/controllers/config.php';
require_once __DIR__ . '/controllers/Router.php';
require_once __DIR__ . '/autoloader.php';

use controllers\Router;

AutoLoader::register();

session_start();

$router = new Router();
$router->handleRequest();
?>