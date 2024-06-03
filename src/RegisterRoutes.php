<?php 

namespace Router;

class RegisterRoutes extends Router {


    /**
     * Altera o nome da classe na classe Router.
     */
    public function __construct() {
        self::$class = __CLASS__;
    }

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
        self::$lastRoute = count(self::$registeredRoutes[$requestMethod]) - 1;
        return;
    }

}

?>