<?php

use Router\RouteMethods;

require 'vendor/autoload.php';


$config = new Router\RouterConfig;
$config->defineRouteControllerConfig([
    'skip_controller_validation' => false
]);

$route = new Router\Router;
$route->get('/{page}', function(RouteMethods $route) {

    $route->setRouteParams(['nome' => 'Keiliel']);
    $route->registerController(function($req, $res) {
        $nome = $req->getUrlParams()['page'];
        $res->html("Olá <b>$nome</b>");
        return;
    });
});

$route->handleRoutes();
?>