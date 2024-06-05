<?php

session_start();

require 'vendor/autoload.php';

$router = new Router\Router;

$router->get('/:id', function() {});

$debug = new Router\RouterDebug;

$debug->routes(true, 'get');

?>