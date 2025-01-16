<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/autoloader.php';

use Project\Database\DataLoaderSQLite;
use Project\Controllers\Router;
use Project\Tools\GenericFormElement;
use Project\Tools\Type\Checkbox;
use Project\Tools\Type\TextInput;
use Project\Classes\Quiz\Question;