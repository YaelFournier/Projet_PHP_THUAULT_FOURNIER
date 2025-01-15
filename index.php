<?php

require_once '../php_resources/autoloader.php';
require_once '../php_resources/DataLoaderSQLite.php';
require_once __DIR__ . '/controllers/Router.php';

use controllers\Router;

AutoLoader::register();

session_start();

$router = new Router();
$router->handleRequest();
?>