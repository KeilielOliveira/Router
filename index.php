<?php

session_start();

require 'vendor/autoload.php';

$route = new Router\Router;

$route->get('/', function() {
    
});

?>