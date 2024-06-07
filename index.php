<?php

use Router\Handle\Request;
use Router\Handle\Response;
use Router\Routes\RouteMethods;

session_start();

require 'vendor/autoload.php';

$router = new Router\Router;

$router->globalMiddlewares(['middleware' => function() {}]);

$router->group('/admin', function(\Router\Group\RouteGroup $group) {

    $group->get('/{page}', function(\Router\Routes\RouteMethods $route) {
        $route->params([
            'parametro' => 'valor'
        ]);
        $route->beforeMiddlewares(function(Request $req, Response $res) {
            $res->setContent("Mensagem do before middleware da rota.<br>");
            return true;
        });
        $route->controller(function(Request $req, Response $res) {
            $res->setContent("Mensagem do controlador da rota.<br>");
        });
        $route->afterMiddlewares(function(Request $req, Response $res) {
            $res->setContent("Mensagem do after middleware da rota.<br>");
            return  true;
        });
    });

    $group->beforeGroupMiddlewares(function(Request $req, Response $res) {
        $res->setContent("Mensagem do before middleware do grupo.<br>");
        return true;
    });
    $group->afterGroupMiddlewares(function(Request $req, Response $res) {
        $res->setContent("Mensagem do after middleware do grupo.<br>");
        return true;
    });

});

$router->handle();

$debug = new Router\RouterDebug;
$debug->groups(false);
$debug->routes(false, 'get');

?>