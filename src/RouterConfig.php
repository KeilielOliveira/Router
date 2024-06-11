<?php 

namespace Router;

class RouterConfig {

    /**
     * Armazena todas as rotas registradas.
     *
     * @var array
     */
    protected static array $registeredRoutes;

    /**
     * Armazena as informações de acesso da ultima rota registrada.
     *
     * @var array
     */
    protected static array $lastRegisteredRoute;

    /**
     * Salva todas as expressões regulares.
     *
     * @var array
     */
    protected static array $regexp;

    /**
     * Armazena os middlewares globais registrados.
     *
     * @var array
     */
    protected static array $globalMiddlewares;

    /**
     * Armazena todos os caracteres que podem ser usados para  a geração de tokens.
     *
     * @var string
     */
    protected static string $chars;

    /**
     * Armazena todos os grupos registrados.
     *
     * @var array
     */
    protected static array $groups;

    /**
     * Inicia as configurações da classe.
     */
    public function __construct() {
        $this->init();
        $this->initRegexp();
        $this->initGlobal();
        $this->initChars();
    }

    /**
     * Inicia o array que armazena as rotas registradas.
     *
     * @return void
     */
    private function init() : void {
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
    private function initRegexp() : void {
        self::$regexp = [
            'route_regexp' => $this->makeRouteRegexp(),
            'url_regexp_delimiter' => $this->makeUrlRegexpDelimiter(),
            'route_query_regexp' => $this->makeRouteQueryRegexp(),
        ];
        return;
    }

    /**
     * Monta a expressão regular usada para validar rotas.
     * 
     * @return string
     */
    private function makeRouteRegexp() : string {
        $regexpPart = "[a-zA-Z0-9-_]+|\{[a-zA-Z0-9-_]+((:|=).+)?\}";
        $regexp = "/^(\/(($regexpPart)(\/($regexpPart))*)?)(:[a-zA-Z0-9-_]+(&[a-zA-Z0-9-_]+)*)?$/";
        return $regexp;
    }

    /**
     * Monta a expressão regualar que marca o fim da url de uma rota.
     *
     * @return string
     */
    private function makeUrlRegexpDelimiter() : string {
        $regexp = "/:([a-zA-Z0-9-_]+(&[a-zA-Z0-9-_]+)*)$/";
        return $regexp;
    }

    private function makeRouteQueryRegexp() : string {
        $regexp = "/(:([a-zA-Z0-9-_]+(&[a-zA-Z0-9-_]+)*))/";
        return $regexp;
    }

    /**
     * Inicia as variveis que armazenam configurações globais.
     *
     * @return void
     */
    private function initGlobal() : void {
        if(empty(self::$globalMiddlewares)) {
            self::$globalMiddlewares = [
                'before_middlewares' => [],
                'after_middlewares' => []
            ];
        }
    }
    
    /**
     * Inicia a variavel de armazenamento dos caracteres validos na geração de tokens.
     *
     * @return void
     */
    private function initChars() : void {
        self::$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    }

}

?>