<?php

use Router\Error\RouteError;
use Router\Group\RouteGroup;
use Router\Handle\Request;
use Router\Handle\Response;
use Router\RouterDebug;
use Router\Routes\RouteMethods;

session_start();

require 'vendor/autoload.php';

$router = new Router\Router;

$router->globalMiddlewares('before', [function(Request $req, Response $res) {

    $requestMethod = $req->requestMethod();
    $url = $req->url();
    $userId = $req->requestId();

    $res->setBeforeContent("Sobre a requisição<br>");
    $res->setBeforeContent("Metodo da requisição: $requestMethod<br>");
    $res->setBeforeContent("Url da requisição: $url<br>");
    $res->setBeforeContent("ID do usuario: $userId<br>");
}]);

$router->get('/', function(RouteMethods $route) {

    $route->params(['name' => 'Keiliel Oliveira']);
    $route->controller(function(Request $req, Response $res) {

        $name = $req->params('name');
        $res->setContent("Bem vindo <b>$name</b>!<br><br>");
    });
    $route->beforeMiddlewares(function(Request $req, Response $res) {
        $token = $req->csrfToken();
        $res->setContent("Token CSRF: $token<br><br>");
    });
    $route->afterMiddlewares(function(Request $req, Response $res) {
        $queryParams = $req->queryParams();
        $urlParams = $req->urlHiddenParams();
        $params = $req->params();

        $res->setAfterContent("Sobre os parametros da rota");
        $res->setAfterContent("<br>Parametros GET: " . count($queryParams));
        $res->setAfterContent("<br>Parametros da url: " . count($urlParams));
        $res->setAfterContent("<br>Parametros da rota: " . count($params));
    });

});

$router->group('/{page}', function(RouteGroup $group) {

    $group->beforeGroupMiddlewares(function(Request $req, Response $res) {
        $res->setContent("Conteudo do before middleware do grupo.<br><br>");
    });

    $group->afterGroupMiddlewares(function(Request $req, Response $res) {
        $res->setContent("Conteudo do after middleware do grupo.");
    });

    $group->get('', function(RouteMethods $route) {
        
        $route->params(['name' => 'Keiliel Oliveira']);
        $route->controller(function(Request $req, Response $res) {

            $name = $req->params('name');
            $page = $req->urlHiddenParams('page');
            $res->setContent("Bem vindo a pagina <b>$page $name</b>!<br>");
        });
        $route->afterMiddlewares(function(Request $req, Response $res) {
            $res->setContent("Token: {csrf_token}<br><br>");
        });
    });

});

$router->globalMiddlewares('after', [function(Request $req, Response $res) {
    $res->setAfterContent("<br>Rota executada com sucesso!");
}]);

$router->error(404, function(RouteError $e) {
    $e->params(['code' => 404]);

    $e->controller(function(Request $req, Response $res) {
        $errorCode = $req->params('code');
        $url = $req->url();
        $res->setBeforeContent("Erro $errorCode<br>");
        $res->setContent("Ocorreu um erro!<br>");
        $res->setAfterContent("A url <b>$url</b> não é acessivel.");
    });
});

$router->handle();

?>