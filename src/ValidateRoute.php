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
        if(preg_match($regexp, $route)) {
            //Se a rota passada for valida.
            return true;
        }

        if(self::$validateRouteConfig['return_exception']) {
            //Se for para retornar uma exceção.
            throw new Exception("A rota <b$route></b> não é valida!");
        }
        return false;
    }

}

?>