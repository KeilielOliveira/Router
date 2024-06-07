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
            echo '<hr>';
            echo nl2br(print_r($routes, true));
            echo '<hr>';
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
            echo '<hr>';
            echo nl2br(print_r($globalMiddlewares, true));
            echo '<hr>';
        }
        return $globalMiddlewares;
    }

    /**
     * Depura os grupos de rotas.
     * 
     * @return array
     */
    public function groups(bool $print = true, string | null $group = null) : array {
        $groups = self::$groups;
        if($group !== null) {
            $groups = $groups[$group];
        }

        if($print) {
            //Se for para exibir os grupos de rotas..
            echo '<hr>';
            echo nl2br(print_r($groups, true));
            echo '<hr>';
        }
        return $groups;
    }

}

?>