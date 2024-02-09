<?php

require('vendor/autoload.php');

Router\Router::get('/', function($route) {
    $route->setParams([
        'nome' => 'Keiliel Oliveira'
    ]);
    $route->setName('home');
    $route->debug();
});

?>