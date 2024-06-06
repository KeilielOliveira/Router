<?php

use Router\Handle\Request;
use Router\Handle\Response;
use Router\Routes\RouteMethods;

session_start();

require 'vendor/autoload.php';

$router = new Router\Router;

$router->get('/{page}', function(RouteMethods $route) {

    $route->params([
        'id' => 100
    ]);

    $route->beforeMiddlewares(function(Request $req, Response $res) {
        $res->setBeforeContent("Conteudo do before middleware<br>");
        return true;
    });

    $route->controller(function(Request $req, Response $res) {
        $page = $req->urlHiddenParams('page');
        $res->setContent("Bem vindo a pagina <b>$page</b><br>");
        return;
    });

    $route->afterMiddlewares([function(Request $req, Response $res) {
        $res->setAfterContent("Conteudo do afterMiddleware");
        return true;
    }]);

});

$router->handle();

$debug = new Router\RouterDebug;
$debug->routes(false, 'get');

?>