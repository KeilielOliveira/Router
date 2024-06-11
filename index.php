<?php

use Router\Group\RouteGroup;
use Router\Handle\Request;
use Router\Handle\Response;
use Router\RouterDebug;
use Router\Routes\RouteMethods;

session_start();

require 'vendor/autoload.php';

$router = new Router\Router;

$router->globalMiddlewares('after', [function(Request $req, Response $res) {
    $res->setContent("Conteudo do after middleware global<br>");
}]);

$router->get('/', function(RouteMethods $route) {

    $route->controller(function(Request $req, Response $res) {
        $url = $req->url();
        $res->setContent("URL: $url<br>Token: {csrf_token}<br>");
    });

    $route->beforeMiddlewares(function(Request $req, Response $res) {
        $res->setContent("Conteudo do before middleware da rota.<br>");
    });

    $route->afterMiddlewares(function(Request $req, Response $res) {
        $res->setContent("Conteudo do after middleware da rota.<br>");
    });

});

$router->group('/{user}', function(RouteGroup $group) {

    $group->get('/{id:[0-9]{8}}', function(RouteMethods $route) {

        $route->controller(function(Request $req, Response $res) {
            $url = $req->url();
            $res->setContent("URL: $url<br>Token: {csrf_token}<br>");
        });

    });

    $group->beforeGroupMiddlewares(function(Request $req, Response $res) {
        $res->setContent("Conteudo do before middleware do grupo.<br>");
    });
    
    $group->afterGroupMiddlewares(function(Request $req, Response $res) {
        $user = $req->urlHiddenParams('user');
        $id = $req->urlHiddenParams('id');

        $res->setContent("Bem vindo <b>$user</b><br>ID: $id<br>");
    });
});

$router->globalMiddlewares('before', [function(Request $req, Response $res) {
    $res->setContent("Conteudo do before middleware global<br>");
}]);

$router->handle();

$debug = new RouterDebug;
$debug->routes(false);

?>