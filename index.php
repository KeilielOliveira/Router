<?php

require('vendor/autoload.php');

$debug = Router\Debug::setDebugMode();
$debug->skipClassOrFunctionValidate();

Router\Router::globalMiddlewares(function($middleware) {
    $middleware->before(function($req, $res, $next) {
        throw new Exception('', 401);
    });
    return $middleware->return();
});

Router\Router::get('/', function($route) {
    $route->controller(function($req, $res) {
        $res->setContent('Rota executada!');
    });
    return $route->return();
});

Router\Router::error(401, function($params) {
    echo "Erro 401: Você não tem permição para acessar a pagina $params[page]";
}, [
    'page' => 'Home'
]);

Router\Router::handleRoutes();

?>