<?php

require('vendor/autoload.php');

$debug = Router\Debug::setDebugMode();
$debug->skipClassOrFunctionValidate();

use Router\Router;

Router::group(function($group) {
    $group->prefix('/{page}');
    $group->groupParams(['user' => 'Admin']);
    $group->groupController('MeuControloadorDeGrupo@Controlador');
    $group->groupMiddlewares(function($middlewares) {
        $middlewares->before('MeuMiddleware')
        ->in('MeuMiddleware@Middleware')->after(function() {});

        return $middlewares->return();
    }); 

    $group->get('/{id=numeric}', function($route) {
        $route->setName('home');
        $route->setParams(['id' => 12]);
        $route->controller('MeuControlador@Controlador');
        $route->middlewares(function($middlewares) {
            $middlewares->before('MeuMiddlewareDeRota@middleware');

            return $middlewares->return();
        });
        return $route->return();
    });

    $group->return();
});

Router::get('/home', function($route) {
    return $route->return();
});

$debug->showRoutes();
?>