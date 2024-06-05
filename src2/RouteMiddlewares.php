<?php 

namespace Router;

use Exception;
use ReflectionMethod;

class RouteMiddlewares extends RouterConfig {

    /**
     * Verifica se os middlewares passados são validos.
     * 
     * @param string|array|callable $middlewares
     * @return bool
     */
    public function isValidMiddlewares(string | array | callable $middlewares) {
        if(is_string($middlewares) || is_callable($middlewares)) {
            //Se foi passado um unico middleware.
            $middlewares = [$middlewares];
        }


    }

    /**
     * Executa os middlewares passados.
     *
     * @param array $middlewares
     * @param array $args
     * @return bool
     */
    public function executeMiddlewares(array $middlewares, array $args) {
        foreach ($middlewares as $key => $middleware) {
            //Percorre cada middleware.

            //Se for um middleware global, recupera o middleware.
            if(isset(self::$globalMiddlewares[$middleware])) {
                $middleware = self::$globalMiddlewares[$middleware];
            }

            $middlewareResponse = null;
            if(is_callable($middleware)) {
                //Se o middleware for uma função.
                $middlewareResponse = $middleware(...$args);
            }else {
                //Se o middleware for uma classe.

                if(str_contains($middleware, '@')) {
                    //Se for para executar um metodo especifico.
                    [$class, $method] = explode('@', $middleware);
                }else {
                    //Se não for para executar um middleware especifico.
                    [$class, $method] = [$middleware, 'middleware'];
                }

                $reflectionMethod = new ReflectionMethod($class, $method);
                if($reflectionMethod->isPublic()) {
                    //Se o metodo for publico.
                    $middleware = new $class;
                    $middlewareResponse = $middleware->$method(...$args);
                }else {
                    //Se o metodo for estatico.
                    $middlewareResponse = $class::$method(...$args);
                }
            }

            if($middlewareResponse == false) {
                //Se um dos middlewares retornar false.
                return false;
            }
        }
        return true;
    }

    /**
     * Verifica se os middlewares globais passados são validos.
     *
     * @param array $globalMiddlewares
     * @return boolean
     */
    public function isValidGlobalMiddlewares(array $globalMiddlewares) {
        foreach ($globalMiddlewares as $name => $middleware) {
            //Percorre cada middleware.
            if(is_numeric($name)) {
                //Se um dos middlewares não tiver um nome proprio.
                throw new Exception("O middleware <b>$name</b> deve possuir um nome proprio!");
            }
        }
        return $this->isValidMiddlewares($globalMiddlewares);
    }


}

?>