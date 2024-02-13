<?php

require('vendor/autoload.php');

$debug = Router\Debug::setDebugMode();
$debug->skipClassOrFunctionValidate();

use Router\Router;

Router::get('/{page}', function($route) {

    $route->middlewares(function($m) {
        $m->before(function($req, $next) {
            return $next($req);
        })->after(function($req, $next) {
            //return $next($req);
        });

        return $m->return();
    });

    $route->controller(function($req) {
        return 'Hello!';
    });

    return $route->return();
});

Router::handleRoutes();

//$debug->showRoutes();
?>