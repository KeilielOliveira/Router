<?php

namespace Router\Routes;

use Router\RouterConfig;
use Router\RouterException;

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

        //Caso a rota seja invalida.
        $exception = new RouterException("A rota <b>$route</b> é invalida.", 103);
        $exception->additionalContent([
            'main action' => 'registro da rota',
            'action' => 'validação da rota'
        ]);
        throw $exception;
    }

    public function routeExists(string $requestMethod, string $route) : bool {
        if(isset(self::$registeredRoutes[$requestMethod][$route])) {
            //Se a rota existir;
            return true;
        }

        $message = "A rota <b>$route</b> do tipo <b>$requestMethod</b> não foi registrada";
        $exception = new RouterException($message, 104);
        $exception->additionalContent([
            'main action' => 'registro da rota',
            'action' => 'verificação da existencia da rota'
        ]);
        throw $exception;
    }
    

}

?>