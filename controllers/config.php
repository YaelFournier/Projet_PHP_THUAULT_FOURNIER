<?php
namespace Project\Config;

define('BASE_PATH', realpath(__DIR__ . '/..'));

define('CRTLS_PATH', BASE_PATH . '/controllers');
define('TOOL_PATH', BASE_PATH . '/tools');
define('VIEWS_PATH', BASE_PATH . '/views');

date_default_timezone_set('Europe/Paris');

define('CURRENT_PAGE', basename($_SERVER['PHP_SELF'], '.php'));
