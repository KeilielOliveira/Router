<?php 

namespace Router;

class RegisterRoutes extends RouterConfig {

    /**
     * Registra a rota passada.
     *
     * @param string $requestMethod
     * @param array $route
     * @return void
     */
    public function registerRoute(string $requestMethod, mixed $route) {
        $requestMethod = strtoupper($requestMethod);
        $route = array_merge($route, [
            'route_params' => array(),
            'controller' => null,
            'middlewares' => [
                'before_middlewares' => array(),
                'after_middlewares' => array()
            ]
        ]);
        self::$registeredRoutes[$requestMethod][] = $route;
        
        self::$lastRoute = [
            'request_method' => $requestMethod,
            'route_index' => count(self::$registeredRoutes[$requestMethod]) - 1];
        return;
    }

    /**
     * Registra os parametros da rota.
     *
     * @param array $params
     * @return void
     */
    public function registerRouteParams(array $params) {
        $requestMethod = self::$lastRoute['request_method'];
        $routeIndex = self::$lastRoute['route_index'];
        self::$registeredRoutes[$requestMethod][$routeIndex]['route_params'] = $params;
        return;
    }

    /**
     * Registra o controlador da rota.
     * 
     * @param string|callable $controller
     * @return void
     */
    public function registerRouteController(string | callable $controller) {
        $requestMethod = self::$lastRoute['request_method'];
        $routeIndex = self::$lastRoute['route_index'];
        self::$registeredRoutes[$requestMethod][$routeIndex]['controller'] = $controller;
        return;
    }

    /**
     * Registra os middlewares da rota.
     * 
     * @param string|array|callable $middlewares
     * @param string $middlewaresType
     * @return void
     */
    public function registerRouteMiddlewares(string | array | callable $middlewares, string $middlewaresType) {
        $requestMethod = self::$lastRoute['request_method'];
        $routeIndex = self::$lastRoute['route_index'];
        self::$registeredRoutes[$requestMethod][$routeIndex]['middlewares'][$middlewaresType] = $middlewares;
        return;
    }

}

?>