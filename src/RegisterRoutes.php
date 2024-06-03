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
    public function registerRoute(string $requestMethod, array $route) {
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
     * Adiciona itens novos na ultima rota registrada.
     *
     * @param mixed $value
     * @return void
     */
    public function registerInRoute(mixed $value) {
        //Recupera a rota.
        $requestMethod = self::$lastRoute['request_method'];
        $index = self::$lastRoute['route_index'];
        $route = self::$registeredRoutes[$requestMethod][$index];

        //Registra o novo item a rota.
        $route = array_merge($route, $value);
        self::$registeredRoutes[$requestMethod][$index] = $route;
        return;
    }

}

?>