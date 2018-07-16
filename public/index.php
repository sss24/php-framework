<?php

error_reporting(-1);

use vendor\core\Router;
require '../vendor/libs/functions.php';

$query = $_SERVER['QUERY_STRING'];

define('ROOT', dirname(__DIR__));

spl_autoload_register(function ($className) {
    debug($className);
    $file = ROOT . '/' . str_replace('\\', '/', $className) . '.php';
    if (is_file($file)) {
        require_once $file;
    }
});

Router::add('^page/?(?P<action>[a-z-]+)?$', ['controller' => 'posts']);
//default rules
Router::add('^$', ['controller' => 'Main', 'action' => 'index']);
Router::add('^(?P<controller>[a-z-]+)/?(?P<action>[a-z-]+)?$');
Router::dispatch($query);



