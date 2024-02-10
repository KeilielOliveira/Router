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
        $this->validate = new Validate();
        $this->utils = new Utils();
        $route = $this->utils->prepareRoute($route);
        $this->route = $route;
    }

    /**
     * Defini parametros que serão armazenados dentro da rota.
     *
     * @param array $params: Os parametros que serão adicionados.
     * @return self
     */
    public function setParams(array $params) {
        if(is_array($params)) {
            $this->route['params'] = $params;
            return $this;
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

            throw new Exception('O nome deve ser uma string e não pode ser vazio!');
        }catch(Exception $e) {
            echo $e->getMessage();
            echo '<br><hr><br>';
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

            throw new Exception('O controloador passado não é valido!');
        }catch(Exception $e) {
            echo $e->getMessage();
            echo '<br><hr><br>';
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

            throw new Exception('O callback deve ser uma função!');
        }catch(Exception $e) {
            echo $e->getMessage();
            echo '<br><hr><br>';
        }
    }

    /**
     * Retorna a rota e suas configurações.
     *
     * @return array
     */
    public function return() {
        return $this->route;
    }

    /**
     * Exibe as informações adicionadas na rota atual.
     *
     * @return self
     */
    public function show() {
        echo nl2br(print_r($this->route, true));
        echo '<br><hr><br>';
        return $this;
    }


}

?>