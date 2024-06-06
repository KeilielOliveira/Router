<?php

use Router\Handle\Request;
use Router\Handle\Response;
use Router\Routes\RouteMethods;

session_start();

require 'vendor/autoload.php';

$router = new Router\Router;

$router->get('/{page}:id', function(RouteMethods $route) {

    $route->params([
        'id' => 100
    ]);

    $route->controller(function(Request $req, Response $res) {
        $page = $req->urlHiddenParams('page');
        $res->setContent("Bem vindo a pagina <b>$page</b>");
        $res->setBeforeContent("Conteudo anterior: ");
        $res->setAfterContent(": Conteudo posterior");
        $res->setHeader(['Content-Type' => 'text/html']);
        $res->setStatusCode(200);
    });

});

$router->handle();

$debug = new Router\RouterDebug;
$debug->routes(false, 'get');

?>