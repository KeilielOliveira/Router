<?php 

namespace Router;

use Router\Routes\RouteValidate;

class RouterConfig {

    /**
     * Armazena todas as rotas registradas.
     *
     * @var array
     */
    protected static array $registeredRoutes;

    /**
     * Salva todas as expressões regulares.
     *
     * @var array
     */
    protected static array $regexp;

    /**
     * Inicia as configurações da classe.
     */
    public function __construct() {
        $this->init();
        $this->initRegexp();
    }

    /**
     * Inicia o array que armazena as rotas registradas.
     *
     * @return void
     */
    private function init() {
        if(empty(self::$registeredRoutes)) {
            //Se o array que armazena as rotas registradas ainda não foi iniciado.
            self::$registeredRoutes = [
                "GET" => [], "POST" => [], "PUT" => [], "DELETE" => [], "UPDATE" => [], "PATCH" => []
            ];
        }
        return;
    }

    /**
     * Inicia as expressões regulares usadas pela classe.
     *
     * @return void
     */
    private function initRegexp() {
        self::$regexp = [
            'route_regexp' => $this->makeRouteRegexp()
        ];
        return;
    }

    /**
     * Monta a expressão regular usada para validar rotas.
     * 
     * @return void
     */
    private function makeRouteRegexp() {
        $regexpPart = "[a-zA-Z0-9-_]+|\{[a-zA-Z0-9-_]+((:|=).+)?\}";
        $regexp = "/^(\/($regexpPart(\/($regexpPart)*))?)(:[a-zA-Z0-9-_]+(&[a-zA-Z0-9-_]+)*)?$/";
        return $regexp;
    }
}

?>