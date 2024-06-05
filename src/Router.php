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

    /**
     * Lida com o registro da rota.
     *
     * @param string $requestMethod
     * @param string $route
     * @return boolean
     */
    private function registerRoute(string $requestMethod, string $route) : bool {
        if($this->routeValidate->isValidRoute($route)) {
            //Se a rota for valida.
            $routeConfig = $this->prepareRoute->prepareRoute($route);
            
            $requestMethod = strtoupper($requestMethod);
            if(!isset(self::$registeredRoutes[$requestMethod][$route])) {
                //Se essa rota já não tiver sido registrada.
                self::$registeredRoutes[$requestMethod][$route] = $routeConfig;
                self::$lastRegisteredRoute = [
                    'request_method' => $requestMethod,
                    'route' => $route
                ];
                return true;
            }

            $message = "A rota <b>$route</b> já foi registrada no metodo de requisição HTTP <b>$requestMethod</b>";
            $code = 110;
            $fix = "Registre outra rota ou mude o metodo de requisição HTTP.";
            throw new RouterException($message, $code, $fix);
        }
    }

    public function get(string $route, callable $callback) : void {
        try {
            $this->registerRoute('GET', $route);
            $callback(new Routes\RouteMethods);
        }catch(RouterException $e) {
            $e->throw();
        }
    }

    public function post(string $route, callable $callback) : void {
        try {
            $this->registerRoute('GET', $route);
            $callback(new Routes\RouteMethods);
        }catch(RouterException $e) {
            $e->throw();
        }
    }

    public function put(string $route, callable $callback) : void {
        try {
            $this->registerRoute('GET', $route);
            $callback(new Routes\RouteMethods);
        }catch(RouterException $e) {
            $e->throw();
        }
    }

    public function delete(string $route, callable $callback) : void {
        try {
            $this->registerRoute('GET', $route);
            $callback(new Routes\RouteMethods);
        }catch(RouterException $e) {
            $e->throw();
        }
    }

    public function update(string $route, callable $callback) : void {
        try {
            $this->registerRoute('GET', $route);
            $callback(new Routes\RouteMethods);
        }catch(RouterException $e) {
            $e->throw();
        }
    }

    public function patch(string $route, callable $callback) : void {
        try {
            $this->registerRoute('GET', $route);
            $callback(new Routes\RouteMethods);
        }catch(RouterException $e) {
            $e->throw();
        }
    }

}

?>