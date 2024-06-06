<?php 

namespace Router;

class RouterDebug extends RouterConfig {

    /**
     * Depura as rotas registradas.
     */
    public function routes(bool $print = true, string $requestMethod = "", int | bool $route = false) : array {
        $requestMethod = strtoupper($requestMethod);
        if($requestMethod != "") {
            //Se um metodo de requisição HTTP foi passado.

            $routes = self::$registeredRoutes[$requestMethod];
            if($route) {
                //Se um indice de rota foi passado.
                $routes = $routes[$route];
            }
        }else {
            //Se nrnhum metodo de requisição HTTP foi passado.
            $routes = self::$registeredRoutes;
        }

        if($print) {
            //Se for para exibir as rotas.
            echo nl2br(print_r($routes, true));
        }
        return $routes;
    }

    /**
     * Depura os middlewares globais.
     *
     * @param boolean $print
     * @return array
     */
    public function globalMiddlewares(bool $print = true) : array {
        $globalMiddlewares = self::$globalMiddlewares;
        if($print) {
            //Se for para exibir os middlewares globais.
            echo nl2br(print_r($globalMiddlewares, true));
        }
        return $globalMiddlewares;
    }

}

?>