<?php

use Router\Handle\Request;
use Router\Handle\Response;
use Router\Routes\RouteMethods;

session_start();

require 'vendor/autoload.php';

$router = new Router\Router;

$router->globalMiddlewares(['middleware' => function() {}]);

$router->group('/admin', function(\Router\Group\RouteGroup $group) {

    $group->beforeGroupMiddlewares(function() {});
    $group->afterGroupMiddlewares(function() {});

});

$router->handle();

$debug = new Router\RouterDebug;
$debug->groups();

?>