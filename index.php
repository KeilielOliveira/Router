<?php

use Router\RouteMethods;

require 'vendor/autoload.php';


$config = new Router\RouterConfig;
$config->defineRouteControllerConfig([
    'skip_controller_validation' => true
]);

$route = new Router\Router;
$route->get('/', function(RouteMethods $route) {

    $route->setRouteParams(['nome' => 'Keiliel']);
    $route->registerController('function() {}');
});

$debug = new Router\DebugRoutes;

$debug->print('get', 0);
?>