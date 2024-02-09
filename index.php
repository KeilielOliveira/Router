<?php

require('vendor/autoload.php');

$debug = Router\Debug::setDebugMode();
$debug->skipClassOrFunctionValidate();

use Router\Router;

Router::get('/', function($route) {
    $route->setParams([
        'nome' => 'Keiliel Oliveira'
    ]);
    $route->setName('home');
    $route->controller('MeuControlador@MeuMetodo');
    $route->middlewares(function($middleware) {
        return $middleware
        ->before('middleware@before')
        ->in(function() {})
        ->after('afterMiddleware')
        ->before(['teste', 'teste2'])
        ->return();
    });
    return $route->return();
});

Router::get('/', function($route) {
    $route->setName('home');

    return $route->return();
});

$debug->showRoutes();

?>