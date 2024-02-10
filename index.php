<?php

require('vendor/autoload.php');

$debug = Router\Debug::setDebugMode();
$debug->skipClassOrFunctionValidate();

use Router\Router;

Router::get('/', function($route) {
    $route->setName('home');
    $route->controller('t');
    $route->middlewares(function($middleware) {
        $middleware->before('t');
        return $middleware->return();
    });


    return $route->return();
});

$debug->showRoutes();

?>