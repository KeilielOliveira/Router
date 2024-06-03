<?php 

require 'vendor/autoload.php';


$route = new Router\Router;

$route->get('/', function() {

});

$debug = new Router\DebugRoutes;

$debug->print('get', 0);
?>