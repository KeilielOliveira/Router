<?php

session_start();

use Router\DebugRoutes;
use Router\RequestResponse;
use Router\RouteMethods;
use Router\RouteRequest;

require 'vendor/autoload.php';


$config = new Router\RouterConfig;
$config->defineRouteControllerConfig([
    'skip_controller_validation' => false
]);
$config->defineRouteMiddlewaresConfig([
    'skip_middlewares_validation' => true
]);

$route = new Router\Router;
$route->get('/{page}', function(RouteMethods $route) {

    $route->setRouteParams(['nome' => 'Keiliel']);
    $route->registerController(function(RouteRequest $req, RequestResponse $res) {
        $page = $req->getUrlParams()['page'];
        //$res->html("Bem vindo a pagina <b>$page</b><br><p>{csrf_route_token}</p>");
        return;
    });
    $route->registerBeforeMiddlewares(function(RouteRequest $req, RequestResponse $res) {
        $res->html("Resultado do before middleware!");
        return true;
    });
    $route->registerAfterMiddlewares(function(RouteRequest $req, RequestResponse $res) {
        $res->html("Resultado do after middleware!");
    });
});

$route->handleRoutes();

$debug = new DebugRoutes;
//$debug->print('get', 0);
?>