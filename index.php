<?php

use Router\Group\RouteGroup;
use Router\RouterDebug;
use Router\Routes\RouteMethods;

session_start();

require 'vendor/autoload.php';

$router = new Router\Router;

$router->group('/{page}', function(RouteGroup $group) {

    $group->get('/{id}:id&token', function(RouteMethods $route) {

        $route->controller(function() {});

        $route->afterMiddlewares(function() {});

    });
    $group->afterGroupMiddlewares(function() {});

});




$debug = new RouterDebug;
$debug->routes();
?>