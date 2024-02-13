<?php 

namespace Router;

use Exception;

class Middlewares {

    //Instancias de classes.
    private $validate, $utils;

    //Informações da classe.
    private $middlewares;

    public static $content;

    /**
     * Inicia variaveis uteis.
     */
    public function __construct() {
        $this->middlewares = [
            'before' => [],
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

                throw new Exception('O middleware não é valido!<br>' . $middlewares);
            }
        }catch(Exception $e) {
            echo $e->getMessage();
            echo '<br><hr><br>';
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

    /**
     * Executa os middlewares que são passados.
     *
     * @param array $middlewares: Os middlewares a serem executados.
     * @param array $params: Parametros que serão passados aos middlewares.
     * @return bool
     */
    public function executeMiddlewares(array $middlewares, array $params) {
        //Se não existir middlewares a serem executados.
        if(empty($middlewares)) {
            return true;
        }        

        $utils = new Utils();
        $execute = function($params) use (&$execute, &$middlewares, &$utils) {

            $next = function($params) use (&$execute, &$middlewares, &$utils) {
                return $execute($params);
            };

            //Se há middlewares a serem executados.
            if(count($middlewares) > 0) {

                $middleware = array_shift($middlewares);
                
                $result = $utils->executeClassOrFunction($middleware, [$params, $next]);

                if($result) {
                    $next($params);
                }else if($result === false || $result === null) {
                    return false;
                }
                
            }

            return true;
        };


        return $execute($params);
    }

}

?>