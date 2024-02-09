<?php 

namespace Router;

use Exception;

class Router {

    public static $routes = []; //Armazena todas as rotas e suas informações.

    public static function get(string $route, callable $callback) {
        try {
            $validate = new Validate();
            if(is_callable($callback) && $validate->isValidUri($route)) {
                //Se o callback for uma função e a URI for valida.
                $route = $callback(new Methods($route));
                self::addRoute($route, 'GET');
                return true;
            }
        }catch(Exception $e) {

        }
    }

    /**
     * Registra a rota.
     *
     * @param array $route: As informações da rota.
     * @param string $method: O metodo de requisição HTTP da rota.
     * @return void
     */
    protected static function addRoute(array $route, string $method) {
        $reference = isset($route['reference']) ? $route['reference'] : $route['uri'];
        self::$routes[$method][$reference] = $route;
        return;
    }

}

?>