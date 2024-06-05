<?php

namespace Router\Routes;

use Router\Interfaces\RouteMethodsInterface;
use Router\RouterConfig;
use Router\RouterException;

class RouteMethods extends RouterConfig implements RouteMethodsInterface {

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
        if(isset(self::$registeredRoutes[$requestMethod][$route])) {
            //Se a rota existir.
            self::$registeredRoutes[$requestMethod][$route][$key] = $value;
            return true;
        }

        $message = "Não foi possivel encontrar a ultima rota registrada!";
        $code = 104;
        throw new RouterException($message, $code);
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

    public function beforeMiddleware(string|array|callable $beforeMiddlewares): void {
        try {

        }catch(RouterException $e) {
            $e->throw();
        }
    }

}

?>