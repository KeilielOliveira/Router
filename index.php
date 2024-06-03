<?php

use Router\RouteMethods;

require 'vendor/autoload.php';


$config = new Router\RouterConfig;
$config->defineRouteControllerConfig([
    'skip_controller_validation' => false
]);

$route = new Router\Router;
$route->get('/{page=home}:token', function(RouteMethods $route) {

    $route->setRouteParams(['nome' => 'Keiliel']);
    $route->registerController(function() {
        echo 'Aqui';
    });
});

$route->handleRoutes();
?>