<?php 

namespace Router;

use Exception;

class Middlewares {

    //Instancias de classes.
    private $validate, $utils;

    //Informações da classe.
    private $middlewares;

    /**
     * Inicia variaveis uteis.
     */
    public function __construct() {
        $this->middlewares = [
            'before' => [],
            'in' => [],
            'after' => []
        ];

        $this->validate = new Validate();
        $this->utils = new Utils();
    }

    /**
     * Registra os before middlewares que serão executados antes do controlador da rota.
     *
     * @param string|array|callable $middlewares: Os middlewares a serem registrados.
     * @return self
     */
    public function before(string|array|callable $middlewares) {
        $this->addMiddlewares($middlewares, 'before');
        return $this;
    }

    /**
     * Registra os in middlewares que serão executados junto do controlador da rota.
     *
     * @param string|array|callable $middlewares: Os middlewares a serem registrados.
     * @return self
     */
    public function in(string|array|callable $middlewares) {
        $this->addMiddlewares($middlewares, 'in');
        return $this;
    }

    /**
     * Registra os after middlewares que serão executados após o controlador da rota.
     *
     * @param string|array|callable $middlewares: Os middlewares a serem registrados.
     * @return self
     */
    public function after(string|array|callable $middlewares) {
        $this->addMiddlewares($middlewares, 'after');
        return $this;
    }

    /**
     * Valida e adiciona os middlewares da rota.
     *
     * @param string|array|callable $middlewares: Os middlewares sendo registrados.
     * @param string $position: A posição dos middlewares.
     * @return self
     */
    protected function addMiddlewares(string|array|callable $middlewares, string $position) {
        try {
            if(is_array($middlewares)) {
                //Se foi passado um array de middlewares.
                foreach ($middlewares as $key => $middleware) {
                    $this->addMiddlewares($middleware, $position);
                }
            }else {
                if($this->validate->isValidClassOrFunction($middlewares)) {
                    $middleware = $this->utils->prepareClassOrFunction($middlewares);
                    $this->middlewares[$position][] = $middleware;
                    return $this;
                }
            }
        }catch(Exception $e) {

        }
    }

    /**
     * Retorna os Middlewares.
     *
     * @return array
     */
    public function return() {
        return $this->middlewares;
    }

}

?>