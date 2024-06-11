<?php 

namespace Router;

use Exception;
use \Router\Interfaces\HttpMethodsInterface;
use Router\Middlewares\GlobalMiddlewares;

class Router extends RouterConfig implements HttpMethodsInterface {

    private Routes\RouteValidate $routeValidate;
    private Routes\PrepareRoute $prepareRoute;
    private GlobalMiddlewares $middlewares;

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
        $this->middlewares = new GlobalMiddlewares;
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

            $requestMethod = strtoupper($requestMethod);
            if(!$this->routeValidate->routeExists($requestMethod, $route)) {
                //Se essa rota não tiver sido registrada, registra a base da rota.
                
                $routeConfig = $this->prepareRoute->prepareRoute($route);
                self::$registeredRoutes[$requestMethod][$route] = $routeConfig;
                self::$lastRegisteredRoute = [
                    'request_method' => $requestMethod,
                    'route' => $route
                ];
                return true;
            }

            //Se a rota já tiver sido registrada.
            $message = "A rota <b>$route</b> do tipo <b>$requestMethod</b> já foi registrada.";
            throw new RouterException($message, 102);
        }

        //Caso a rota seja invalida.
        throw new RouterException("A rota <b>$route</b> é invalida.", 103);
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
            $this->registerRoute('POST', $route);
            $callback(new Routes\RouteMethods);
        }catch(RouterException $e) {
            $e->throw();
        }
    }

    public function put(string $route, callable $callback) : void {
        try {
            $this->registerRoute('PUT', $route);
            $callback(new Routes\RouteMethods);
        }catch(RouterException $e) {
            $e->throw();
        }
    }

    public function delete(string $route, callable $callback) : void {
        try {
            $this->registerRoute('DELETE', $route);
            $callback(new Routes\RouteMethods);
        }catch(RouterException $e) {
            $e->throw();
        }
    }

    public function update(string $route, callable $callback) : void {
        try {
            $this->registerRoute('UPDATE', $route);
            $callback(new Routes\RouteMethods);
        }catch(RouterException $e) {
            $e->throw();
        }
    }

    public function patch(string $route, callable $callback) : void {
        try {
            $this->registerRoute('PATCH', $route);
            $callback(new Routes\RouteMethods);
        }catch(RouterException $e) {
            $e->throw();
        }
    }

    /**
     * Registra middlewares globais.
     *
     * @param array $globalMiddlewares
     * @return void
     */
    public function globalMiddlewares(string $middlewaresType, array $globalMiddlewares) : void {
        try {   
            $this->middlewares->registerGlobalMiddlewares($middlewaresType, $globalMiddlewares);
        }catch(RouterException $e) {
            $e->throw();
        }
    }

    /**
     * Cria um grupo de rotas.
     *
     * @param string $base
     * @param callable $callback
     * @return void
     */
    public function group(string $base, callable $callback) : void {
        try {
            $callback(new Group\RouteGroup($base));
        }catch(RouterException $e) {
            $e->throw();
        }
    }

    /**
     * Lida com a execução das rotas registradas.
     *
     * @return void
     */
    public function handle() : void {
        try {
            new Handle\HandleRoutes;
        }catch(RouterException $e) {
            $e->throw();
        }
    }

}

?>