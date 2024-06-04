<?php 

namespace Router;

use ReflectionMethod;

class RouteMiddlewares extends RouterConfig {

    public function isValidMiddlewares(string | array | callable $middlewares) {
        if(is_string($middlewares) || is_callable($middlewares)) {
            //Se foi passado um unico middleware.
            $middlewares = [$middlewares];
        }

        foreach ($middlewares as $key => $middleware) {
            //Percorre cada middleware.

            if(is_callable($middleware) || self::$routeMiddlewaresConfig['skip_middlewares_validation']) {
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