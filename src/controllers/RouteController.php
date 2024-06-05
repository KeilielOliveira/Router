<?php 

namespace Router\Controller;

use ReflectionMethod;
use Router\RouterConfig;
use Router\RouterException;

class RouteController extends RouterConfig {

    //Instancias de classe.
    private \Router\Routes\RouteValidate $routeValidate;

    /**
     * Inicia as instancias de classes.
     */
    public function __construct() {
        $this->routeValidate = new \Router\Routes\RouteValidate;
    }

    /**
     * Verifica se a rota já não possui um controlador.
     *
     * @param string $requestMethod
     * @param string $route
     * @return void
     */
    private function routeHasController(string $requestMethod, string $route) : void {
        $route = self::$registeredRoutes[$requestMethod][$route];
        //Se a rota não posuir um controlador.
        if(!isset($route['controller'])) {
            return;
        }

        $message = "A rota <b>$route</b> já possui um controlador!";
        $code = 105;
        $exception = new RouterException($message, $code);
        $exception->route($route);
        $exception->requestMethod($requestMethod);
        $exception->action("Verificando se a rota possui um controlador");
        throw $exception;
    }

    public function registerController(string | callable $controller) : bool {
        $requestMethod = self::$lastRegisteredRoute['request_method'];
        $route = self::$lastRegisteredRoute['route'];
        if($this->isValidController($controller)) {
            //Se o controlador for valido.
            if($this->routeValidate->routeExists($requestMethod, $route)) {
                //Se a rota existir.
                $this->routeHasController($requestMethod, $route);

                //Registra o controlador.
                self::$registeredRoutes[$requestMethod][$route]['controller'] = $controller;
                return true;
            }
        }

        $message = "O controlador passado não é valido!";
        $code = 101;
        $fix = "Passe um controlador valido.";
        $exception = new RouterException($message, $code, $fix);
        $exception->route($route);
        $exception->requestMethod($requestMethod);
        $exception->action("Validando controlador");
        throw $exception;
    }

    /**
     * Executa o controlador passado.
     * 
     * @return void
     */
    public function executeController(string | callable $controller) : void {
        if(is_callable($controller)) {
            //Se o controlador for uma função.
            $controller();
        }else {
            //Se o controlador for uma classe.

            if(str_contains($controller, '@')) {
                //Se foi definido um metodo especifico para executar.
                [$class, $method] = explode('@', $controller);
            }else {
               //Se não foi definido um metodo especifico para executar. 
               [$class, $method] = [$controller, 'controller'];
            }

            $reflectionMethod = new ReflectionMethod($class, $method);
            if($reflectionMethod->isPublic()) {
                //Se o metodo for publico.
                $class = new $class;
                $class->$method();
            }else {
                //Se o metodo for estatico.
                $class::$method();
            }
        }
    }

    /**
     * Verifica se o controlador passado é valido.
     */
    private function isValidController(string | callable $controller) : bool {
        if(is_callable($controller)) {
            //Se o controlador for uma função valida.
            return true;
        }else {
            //Se o controlador foi passado como uma classe.
            if(str_contains($controller, '@')) {
                //Se foi passado um metodo especifico como controlador.
                [$class, $method] = explode('@', $controller);    
            }else {
                //Se não foi passado um metodo especifico como controlador.
                [$class, $method] = [$controller, 'controller'];
            }

            if(class_exists($class) && method_exists($class, $method)) {
                //Se a classe e o metodo existirem.

                $reflectionMethod = new ReflectionMethod($class, $method);
                if($reflectionMethod->isPublic() || $reflectionMethod->isStatic()) {
                    //Se o metodo for publico ou estatico.
                    return true;
                }
            }
        }
        return false;
    }

}

?>