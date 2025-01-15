<?php

require_once __DIR__ .'/php_ressources/autoloader.php';
require_once __DIR__.'/php_ressources/DataLoaderSQLite.php';
require_once __DIR__ . '/controllers/Router.php';
require_once __DIR__.'/controllers/config.php';

use controllers\Router;

session_start();

$router = new Router();
$router->handleRequest();
?>