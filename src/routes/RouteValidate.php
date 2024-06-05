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
        $message = "A rota <b>$route</b> é invalida.";
        $code = 100;
        $fix = "Passe uma rota valida como <b>/home</b>.";
        $exception = new RouterException($message, $code, $fix);
        $exception->route($route);
        $exception->action("Validação da rota");
        throw $exception;
    }

    public function routeExists(string $requestMethod, string $route) : bool {
        if(isset(self::$registeredRoutes[$requestMethod][$route])) {
            //Se a rota existir;
            return true;
        }

        $message = "A rota <b>$route</b> do metodo de requisição HTTP <b>$requestMethod</b> não existe!";
        $code = 104;
        $exception = new RouterException($message, $code);
        $exception->route($route);
        $exception->requestMethod($requestMethod);
        $exception->action("Verificando se a rota existe");
        throw $exception;
    }

}

?>