<?php

require('vendor/autoload.php');

$debug = Router\Debug::setDebugMode();
$debug->skipClassOrFunctionValidate();

use Router\Router;

Router::get('/{page}', function($route) {

    $route->setParams([
        'nome'=>'Keiliel Oliveira'
    ]);

    $route->middlewares(function($m) {
        $m->before(function($req, $res, $next) {
            return $next($req);
        })->after(function($req, $res, $next) {
            if($res->getContent() == 'Hello!') {
                $res->setContent('O conteudo foi alterado pelo middleware!');
            }
            return $next($req);
        });

        return $m->return();
    });

    $route->controller(function($req, $res) {
        $res->setContent('Hello2!');
        return;
    });

    return $route->return();
});

Router::handleRoutes();

//$debug->showRoutes();
?>