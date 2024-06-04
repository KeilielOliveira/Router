<?php

session_start();

use Router\RequestResponse;
use Router\RouteMethods;
use Router\RouteRequest;

require 'vendor/autoload.php';


$config = new Router\RouterConfig;
$config->defineRouteControllerConfig([
    'skip_controller_validation' => false
]);

$route = new Router\Router;
$route->get('/{page}', function(RouteMethods $route) {

    $route->setRouteParams(['nome' => 'Keiliel']);
    $route->registerController(function(RouteRequest $req, RequestResponse $res) {
        $page = $req->getUrlParams()['page'];
        $res->html("Bem vindo a pagina <b>$page</b><br><p>{csrf_route_token}</p>");
        return;
    });
});

$route->handleRoutes();
?>