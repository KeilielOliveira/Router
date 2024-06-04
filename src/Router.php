<?php 

namespace Router;

use Exception;
use \Router\Interfaces\HttpMethodsInterface;

class Router extends RouterConfig implements HttpMethodsInterface {

    private Routes\RouteValidate $routeValidate;

    /**
     * Inicia as configurações base da classe.
     */
    public function __construct() {
        parent::__construct();
        $this->initInstances();
    }

    /**
     * Inicia as instancias de classes usadas.
     *
     * @return void
     */
    private function initInstances() {
        $this->routeValidate = new Routes\RouteValidate;
    }


    public function get(string $route, callable $callback) {
        try {
            if($this->routeValidate->isValidRoute($route)) {

            }
        }catch(RouterException $e) {
            $e->throw();
        }
    }

}

?>