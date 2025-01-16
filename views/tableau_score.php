<?php
namespace Project\Views;

use Project\Classes\Quiz\Score;
use Project\Database\DataLoaderSQLite;

$pdo = DataLoaderSQLite::getPDO();

Score::affiche($pdo);

?>