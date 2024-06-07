<?php 

namespace Router\Middlewares;

use Router\RouterConfig;
use ReflectionMethod;
use Router\RouterException;

class RouteMiddlewares extends RouterConfig {

    private \Router\Routes\RouteValidate $routeValidate;
    private GlobalMiddlewares $middlewares;

    /**
     * Inicia as instancias de classes.
     */
    public function __construct() {
        $this->routeValidate = new \Router\Routes\RouteValidate;
        $this->middlewares = new GlobalMiddlewares;
    }

    /**
     * Registra middlewares em uma rota.
     * 
     * @return void
     */
    public function registerRouteMiddleware(string $type, string | array | callable $middlewares) : void {
        $requestMethod = self::$lastRegisteredRoute['request_method'];
        $route = self::$lastRegisteredRoute['route'];

        if($this->isValidMiddlewares($middlewares)) {
            //Se os middlewares forem validos.

            $middlewares = is_array($middlewares) ? $middlewares : [$middlewares];
            if($this->routeValidate->routeExists($requestMethod, $route)) {
                //Se a rota existir.
                if($this->routeHasMiddleware($type, $requestMethod, $route)) {
                    //Se a rota já possui middlewares.

                    //Recupera os middlewares da rota.
                    $middlewaresType = $type . '_middlewares';
                    $routeMiddlewares = self::$registeredRoutes[$requestMethod][$route];
                    $routeMiddlewares = $routeMiddlewares['middlewares'][$middlewaresType];

                    //Junta os middlewares.
                    $middlewares = array_merge($routeMiddlewares, $middlewares);
                }else {
                    //Se a rota não possui middlewares.
                    $middlewaresType = $type . '_middlewares';
                }

                //Registra os middlewares na rota.
                self::$registeredRoutes[$requestMethod][$route]['middlewares'][$middlewaresType] = $middlewares;
                return;
            } 
        }

        //Lança o erro.
        $message = "Um dos middlewares passados é invalido.";
        $code = 124;
        $exception = new RouterException($message, $code);
        $exception->route($route);
        $exception->requestMethod($requestMethod);
        $exception->action("Registro de $type middlewares");
        throw $exception;
    }

    /**
     * Executa os middlewares passados.
     *
     * @param array $middlewares
     * @return boolean
     */
    public function executeMiddlewares(array $middlewares, array $params = []) : bool {
        foreach($middlewares as $key => $middleware) {
            //Percorre cada middleware a ser executado.

            $result = null;
            if(is_callable($middleware)) {
                //Se o middleware for uma função.
                $result = $middleware(...$params);
            }else {
                //Se o middleware for uma classe.

                if(str_contains($middleware, '@')) {
                    //Se foi passado um metodo especifico para ser executado.
                    [$class, $method] = explode('@', $middleware);
                }else {
                    //Se não foi passado um metodo especifico para ser executado.
                    [$class, $method] = [$middleware, 'middleware'];
                }

                $reflectionMethod = new ReflectionMethod($class, $method);
                if($reflectionMethod->isPublic()){ 
                    //Se o metodo for publico.
                    $class = new $class;
                    $result = $class->$method(...$params);
                }else {
                    //Se o metodo for estatico.
                    $result = $class::$method(...$params);
                }
            }

            if($result !== true) {
                //Se retornar algo diferente de TRUE irá encerrar a execução da rota.
                return false;
            }
        }
        return true;
    }


    /**
     * Verifica se os middlewares são validos.
     * 
     * @return boolean
     */
    private function isValidMiddlewares(string | array | callable $middlewares) : bool {
        $middlewares = !is_array($middlewares) ? [$middlewares] : $middlewares;
        foreach ($middlewares as $key => $middleware) {
            //Percorre cada middleware.

            if(is_callable($middleware)) {
                //Se o middleware for uma função valida.
                continue;
            }else {
                //Se o middleware for uma classe.

                if(str_contains($middleware, '@')) {
                    //Se foi passado um metodo especifico da classe.
                    [$class, $method] = explode('@', $middleware);
                }else {
                    //Se não foi passado um metodo especifico da classe.
                    [$class, $method] = [$middleware, 'middleware'];
                }

                if(class_exists($class) && method_exists($class, $method)) {
                    //Se a classe e o metodo exisitirem.

                    $reflectionMethod = new ReflectionMethod($class, $method);
                    if($reflectionMethod->isPublic() || $reflectionMethod->isStatic()) {
                        //Se o metodo for publico ou estatico.
                        continue;
                    }
                }
            }
            return false;
        }
        return true;
    }

    /**
     * Verifica se a rota já possui middlewares.
     *
     * @param string $type
     * @param string $requestMethod
     * @param string $route
     * @return boolean
     */
    private function routeHasMiddleware(string $type, string $requestMethod, string $route) : bool {
        $middlewaresType = $type . '_middlewares';
        $route = self::$registeredRoutes[$requestMethod][$route];
        if(empty($route['middlewares'][$middlewaresType])) {
            //Se a rota já possui middlewares.
            return true;
        }
        return false;
    }

}

?>