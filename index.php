<?php

require('vendor/autoload.php');

$debug = Router\Debug::setDebugMode();
$debug->skipClassOrFunctionValidate();

$func = function($req, $res) {
    $res->setContent($req['params']['user']);
    return;
};

Router\Router::get('/', function($route) use ($func) {
    $route->setName('home');
    $route->setParams(['user' => 'Admin']);
    $route->controller($func);
    $route->middlewares(function($middleware) {
        /*
        Exemplo de definição de middlewares.
        $middleware->before(['BeforeMiddleware', 'BeforeMiddleware@method'])
        ->after('AfterMiddleware@method');
        */
        return $middleware->return();
    });
    return $route->return();
});

Router\Router::group(function($group) use ($func) {
    $group->prefix('/account');
    $group->groupParams(['user' => 'outro usuario']);
    $group->groupController($func);
    $group->groupMiddlewares(function($middleware) {  
        /*
        Exemplo de definição de middlewares.
        $middleware->before(['BeforeMiddleware', 'BeforeMiddleware@method'])
        ->after('AfterMiddleware@method');
        */
        return $middleware->return();
    });
    $group->get('/me', function($route) {
        //Mesmas definições da rota / registrada anteriormente.
        return $route->return();
    });
    return $group->return();
});

Router\Router::handleRoutes();

?>