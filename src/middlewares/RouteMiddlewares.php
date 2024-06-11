<?php 

namespace Router\Middlewares;

use Router\RouterConfig;
use ReflectionMethod;
use Router\RouterException;

class RouteMiddlewares extends RouterConfig {

    private \Router\Routes\RouteValidate $routeValidate;

    /**
     * Inicia as instancias de classes.
     */
    public function __construct() {
        $this->routeValidate = new \Router\Routes\RouteValidate;
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

                //Recupera os middlewares da rota.
                $middlewaresType = $type . '_middlewares';
                $routeMiddlewares = self::$registeredRoutes[$requestMethod][$route];
                $routeMiddlewares = $routeMiddlewares['middlewares'][$middlewaresType];

                //Junta os middlewares.
                $middlewares = array_merge($routeMiddlewares, $middlewares);

                //Registra os middlewares na rota.
                self::$registeredRoutes[$requestMethod][$route]['middlewares'][$middlewaresType] = $middlewares;
                return;
            } 

            $message = "Não foi possivel encontrar a rota <b>$route</b> do tipo <b>$requestMethod</b>.";
            throw new RouterException($message, 104);
        }

        //Lança o erro.
        $message = "Um dos middlewares passados para a rota <b>$route</b> do tipo <b>$requestMethod</b> é invalido.";
        throw new RouterException($message, 403);
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

            if($result === false) {
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

}

?>