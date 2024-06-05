<?php

use Router\Routes\RouteMethods;

session_start();

require 'vendor/autoload.php';

$router = new Router\Router;

$router->get('/', function(RouteMethods $route) {

    $route->params([
        'id' => 100
    ]);

    $route->controller(function() {});
});

$debug = new Router\RouterDebug;

$debug->routes(true, 'get');

?>