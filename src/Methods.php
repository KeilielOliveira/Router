<?php

namespace Router;

use Exception;

class Methods {

    //Instancia de classes.
    private $validate, $utils;

    //Informações da rota.
    public $route;

    /**
     * Salva as informações relevantes.
     *
     * @param string $route: A rota que está sendo definida.
     */
    public function __construct(string $route) {
        $this->route = ['uri' => $route];
        $this->validate = new Validate();
        $this->utils = new Utils();
    }

    /**
     * Defini parametros que serão armazenados dentro da rota.
     *
     * @param array $params: Os parametros que serão adicionados.
     * @return self
     */
    public function setParams(array $params) {
        try {
            if(is_array($params)) {
                $this->route['params'] = $params;
                return $this;
            }
        }catch(Exception $e) {

        }
    }

    /**
     * Defini um nome customizado para a rota.
     *
     * @param string $name: O nome customizado.
     * @return self
     */
    public function setName(string $name) {
        try {
            if($name != '' && is_string($name)) {
                $this->route['reference'] = $name;
                return $this;
            }
        }catch(Exception $e) {

        }
    }

    /**
     * Registra um controlador para a rota.
     *
     * @param string|callable $controller: O controlador a ser registrado.
     * @return self
     */
    public function controller(string|callable $controller) {
        try {
            if($this->validate->isValidClassOrFunction($controller)) {
                //O controlador é valido.
                $controller = $this->utils->prepareClassOrFunction($controller);
                $this->route['controller'] = $controller;
                return $this;
            }
        }catch(Exception $e) {

        }
    }

    /**
     * Registra middlewares para a rota.
     *
     * @param callable $callback: Função de callback que recebe a instancia da classe de registro de middlewares.
     * @return self
     */
    public function middlewares(callable $callback) {
        try {
            if(is_callable($callback)) {
                $middlewares = $callback(new Middlewares());
                $this->route['middlewares'] = $middlewares;
                return $this;
            }
        }catch(Exception $e) {

        }
    }

    public function debug() {
        echo nl2br(print_r($this->route, true));
    }


}

?>