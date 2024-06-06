<?php

use Router\Handle\Request;
use Router\Handle\Response;
use Router\Routes\RouteMethods;

session_start();

require 'vendor/autoload.php';

$router = new Router\Router;

$router->group('/admin', function(\Router\Group\RouteGroup $group) {

    $group->get('/dashboard', function(\Router\Routes\RouteMethods $route) {

        $route->controller(function() {
            echo 'Aqui';
        });
    });

});

$router->handle();

$debug = new Router\RouterDebug;
$debug->routes();

?>