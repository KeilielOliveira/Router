<?php

session_start();

require 'vendor/autoload.php';

$route = new Router\Router;

$route->get('/{page}', function(Router\RouteMethods $route) {
    $route->registerController(function(Router\RouteRequest $req, Router\RequestResponse $res) {

        $res->html('Olá');
    });

});

$route->error(function(Router\RouterErrors $error) {

    $error->registerError(404, function() {
        echo "Ocorreu um erro!";
    });
});

$route->handleRoutes();

?>