<?php

use Router\Handle\Request;
use Router\Routes\RouteMethods;

session_start();

require 'vendor/autoload.php';

$router = new Router\Router;

$router->get('/{page}:id', function(RouteMethods $route) {

    $route->params([
        'id' => 100
    ]);

    $route->controller(function(Request $req) {
        echo $req->requestMethod();
        echo '<br>';
        echo $req->url();
        echo '<br>';
        echo $req->requestId();
        echo '<br>';
        print_r($req->urlHiddenParams());
        echo '<br>';
        print_r($req->queryParams());
        echo '<br>';
        echo $req->params('id');
    });

});

$router->handle();

$debug = new Router\RouterDebug;
$debug->routes(false, 'get');

?>