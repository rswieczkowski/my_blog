<?php


use libraries\Database;
use models\Post;

require_once 'config/config.php';

spl_autoload_register(function (string $className) {

    $className = str_replace('\\', '/', $className);

    require_once ROOT_PATH . '/' . $className . '.php';
});









