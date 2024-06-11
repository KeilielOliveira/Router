<?php

namespace Router\Routes;

use Router\RouterConfig;

class RouteValidate extends RouterConfig {

    /**
     * Verifica se a rota é valida.
     *
     * @param string $route
     * @return boolean
     */
    public function isValidRoute(string $route) : bool {
        $regexp = self::$regexp['route_regexp']; //Expressão regular usada para validar a rota.
        if(preg_match($regexp, $route)) {
            //Se a rota for valida.
            return true;
        }
        return false;
    }

    /**
     * Verfica se a rota com as informações passadas existe.
     *
     * @param string $requestMethod
     * @param string $route
     * @return boolean
     */
    public function routeExists(string $requestMethod, string $route) : bool {
        $requestMethod = strtoupper($requestMethod);
        if(isset(self::$registeredRoutes[$requestMethod][$route])) {
            //Se a rota existir;
            return true;
        }
        return false;
    }
    

}

?>