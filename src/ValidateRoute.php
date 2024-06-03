<?php 

namespace Router;

use Exception;

class ValidateRoute {

    /**
     * Armazena a expressão regular de validação das rotas.
     *
     * @var string
     */
    private string $regexp;

    /**
     * Armazena a configuração que define se deve ou não retornar uma Exception ao ocorrer um erro.
     *
     * @var boolean
     */
    private bool $returnException;

    /**
     * Defini se deve ou não retornar uma Exception ao ocorrer um erro.
     *
     * @param boolean $returnException
     */
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

    /**
     * Verifica se a rota passada é valida.
     *
     * @param string $route
     * @return boolean
     */
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