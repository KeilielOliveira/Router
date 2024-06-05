<?php 

namespace Router\Interfaces;

interface RouteMethodsInterface {

    /**
     * Defini parametros customizados para a rota, esses parametros podem ser usados pelos middlewares
     * e controladores.
     *
     * @param array $params
     * @return void
     */
    public function params(array $params) : void;


}

?>