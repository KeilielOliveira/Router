<?php 

namespace Router;

use Exception;

class RouteValidator {

    private string $regexp;
    private bool $returnException;

    public function __construct(bool $returnException = true) {
        $this->defineRouteRegexp();
        $this->returnException = $returnException;
    }

    /**
     * Defini a expressão regular de validação de rotas.
     *
     * @return void
     */
    private function defineRouteRegexp() {
        $url = '\/([a-zA-Z0-9-_]+|\{[a-zA-Z0-9-_]+(=.+)?\}(\/([a-zA-Z0-9-_]+|\{[a-zA-Z0-9-_]+(=.+)?\}))*)?';
        $query = '(:[a-zA-Z0-9-_]+(&[a-zA-Z0-9-_]+)*)?';
        $this->regexp = '/^' . $url . $query . '$/';
        return;
    }

    public function isValidRoute(string $route) {
        if(preg_match($this->regexp, $route)) {
            //Se a rota passada for valida.
            return true;
        }

        if($this->returnException) {
            //Se for para retornar uma exceção.
            throw new Exception("A rota <b$route></b> não é valida!");
        }
        return false;
    }

}

?>