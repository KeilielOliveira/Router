<?php

namespace Router\Routes;

use Router\Interfaces\RouteMethodsInterface;
use Router\RouterConfig;
use Router\RouterException;

class RouteMethods extends RouterConfig implements RouteMethodsInterface {

    private RouteValidate $routeValidate;

    /**
     * Inicia a classe.
     */
    public function __construct() {
        $this->routeValidate = new RouteValidate;   
    }

    /**
     * Registra um novo item dentro de uma rota.
     *
     * @param string $key
     * @param mixed $value
     * @return boolean
     */
    private function registerInRoute(string $key, mixed $value) : bool {
        $requestMethod = self::$lastRegisteredRoute['request_method'];
        $route = self::$lastRegisteredRoute['route'];
        if($this->routeValidate->routeExists($requestMethod, $route)) {
            //Se a rota existir.
            self::$registeredRoutes[$requestMethod][$route][$key] = $value;
            return true;
        }

        $message = "Não foi possivel encontrar a rota <b>$route</b> do tipo <b>$requestMethod</b>";
        throw new RouterException($message, 104);
    }

    public function params(array $params): void {
        try {
            $this->registerInRoute("route_params", $params);
        }catch(RouterException $e) {
            $e->throw();
        }
    }

    public function controller(string|callable $controller): void {
        try {
            $routeController = new \Router\Controller\RouteController;
            $routeController->registerController($controller);
        }catch(RouterException $e) {
            $e->throw();
        }
    }

    public function beforeMiddlewares(string|array|callable $beforeMiddlewares): void {
        try {
            $routeMiddlewares = new \Router\Middlewares\RouteMiddlewares;
            $routeMiddlewares->registerRouteMiddleware('before', $beforeMiddlewares);
        }catch(RouterException $e) {
            $e->throw();
        }
    }

    public function afterMiddlewares(string|array|callable $afterMiddlewares): void {
        try {
            $routeMiddlewares = new \Router\Middlewares\RouteMiddlewares;
            $routeMiddlewares->registerRouteMiddleware('after', $afterMiddlewares);
        }catch(RouterException $e) {
            $e->throw();
        }
    }

}

?>