<?php 

namespace Router;

use Exception;
use \Router\Interfaces\HttpMethodsInterface;

class Router extends RouterConfig implements HttpMethodsInterface {

    private Routes\RouteValidate $routeValidate;
    private Routes\PrepareRoute $prepareRoute;

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
        $this->prepareRoute = new Routes\PrepareRoute;
    }

    private function registerRoute(string $requestMethod, string $route) {
        if($this->routeValidate->isValidRoute($route)) {
            //Se a rota for valida.
            $routeConfig = $this->prepareRoute->prepareRoute($route);
            echo nl2br(print_r($routeConfig, true));
        }
    }


    public function get(string $route, callable $callback) {
        try {
            $this->registerRoute('GET', $route);
        }catch(RouterException $e) {
            $e->throw();
        }
    }

}

?>