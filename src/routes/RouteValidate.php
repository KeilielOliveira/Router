<?php

namespace Router\Routes;

use Router\RouterConfig;
use Router\RouterException;

class RouteValidate extends RouterConfig {

    public function isValidRoute(string $route) {
        $regexp = self::$regexp['route_regexp']; //Expressão regular usada para validar a rota.
        if(preg_match($regexp, $route)) {
            //Se a rota for valida.
            return true;
        }

        //Caso a rota seja invalida.
        $message = "A rota <b>$route</b> é invalida.";
        $code = 100;
        $fix = "Passe uma rota valida como <b>/home</b>.";
        throw new RouterException($message, $code, $fix);
    }

}

?>