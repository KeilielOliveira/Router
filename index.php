<?php

require('vendor/autoload.php');

$debug = Router\Debug::setDebugMode();
$debug->skipControllerValidate();

Router\Router::get('/', function($route) {
    $route->setParams([
        'nome' => 'Keiliel Oliveira'
    ]);
    $route->setName('home');
    $route->controller('MeuControlador@MeuMetodo');
    $route->debug();
});

?>