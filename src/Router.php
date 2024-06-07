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

            $message = "Rota já registrada.";
            $code = 110;
            $fix = "Registre outra rota ou mude o metodo de requisição HTTP.";
            $exception = new RouterException($message, $code, $fix);
            $exception->route($route);
            $exception->requestMethod($requestMethod);
            $exception->action("Registro");
            throw $exception;
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

    /**
     * Registra middlewares globais.
     *
     * @param array $globalMiddlewares
     * @return void
     */
    public function globalMiddlewares(array $globalMiddlewares) : void {
        try {   
            $this->middlewares->registerGlobalMiddlewares($globalMiddlewares);
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