<?php

namespace Router;

use Exception;

class Methods {

    public $route;

    /**
     * Salva as informações relevantes.
     *
     * @param string $route: A rota que está sendo definida.
     */
    public function __construct(string $route) {
        $this->route = ['uri' => $route];
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

    public function debug() {
        echo nl2br(print_r($this->route, true));
    }


}

?>