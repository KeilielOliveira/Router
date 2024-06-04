<?php 

namespace Router;

use Exception;

class ValidateRoute extends RouterConfig {

    /**
     * Verifica se a rota passada é valida.
     *
     * @param string $route
     * @return boolean
     */
    public function isValidRoute(string $route) {
        $regexp = self::$regexp['url_regexp'] . self::$regexp['query_regexp'];
        //Se a rota for valida.
        if(preg_match($regexp, $route)) {
            return true;
        }
        throw new Exception("A rota <b>$route</b> é invalida!");
    }

    /**
     * Procura uma rota que bata com a url atual.
     *
     * @return bool|array
     */
    public function validateRoute() {
        $url = isset($_GET['url']) ? '/' . $_GET['url'] : '/';
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $registeredRoutes = self::$registeredRoutes[$requestMethod];
        foreach ($registeredRoutes as $key => $route) {
            //Percorre cada rota registrada no metodo de requisição http atual.

            if(preg_match($route['url_regexp'], $url)) {
                //Se a rota bater com a url atual.
                return $route;
            }
        }
        return false;
    }

    /**
     * Verifica se a url atual possui todos os parametros get.
     *
     * @param array $queryParams
     * @return void
     */
    public function validateQueryParams(array | null $queryParams) {
        if($queryParams != null) {
            //Se foram definidos parametros get para a rota.
            foreach ($queryParams as $key => $param) {
            
                //Se não existir um dos parametros.
                if(!isset($_GET[$param])) {
                    return false;
                }
            }
        }
        return true;
    }

}

?>