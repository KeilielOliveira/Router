<?php 

namespace Router\Middlewares;

use Router\RouterConfig;
use ReflectionMethod;
use Router\RouterException;

class GlobalMiddlewares extends RouterConfig {

    /**
     * Registra middlewares globais.
     *
     * @param array $globalMiddlewares
     * @return void
     */
    public function registerGlobalMiddlewares(array $globalMiddlewares) : void {
        if($this->isValidMiddlewares($globalMiddlewares)) {
            //Se os middlewares forem validos.

            if(empty(self::$globalMiddlewares)) {
                //Se ainda não foi iniciado.
                self::$globalMiddlewares = $globalMiddlewares;
            }else {
                //Se já foi registrado middlewares.
                self::$globalMiddlewares = array_merge(self::$globalMiddlewares, $globalMiddlewares);
            }
            return;
        }

        $message = "Um dos middlewares passados é invalido.";
        $exception = new RouterException($message, 303);
        $exception->action("Registro de middlewares globais.");
        throw $exception;
    }

    /**
     * Verifica se o nome passado é de um middleware global.
     *
     * @param mixed $name
     * @return boolean
     */
    public function isGlobalMiddleware(mixed $name, bool $return = false) : bool | string | callable {
        $globalMiddlewares = self::$globalMiddlewares;
        if(is_string($name) && isset($globalMiddlewares[$name])) {
            //Se existe esse middleware global.
            return $return ? $globalMiddlewares[$name] : true;
        }
        return false;
    }

    /**
     * Verifica se os middlewares são validos.
     * 
     * @return boolean
     */
    private function isValidMiddlewares(array $middlewares) : bool {
        $middlewares = !is_array($middlewares) ? [$middlewares] : $middlewares;
        foreach ($middlewares as $key => $middleware) {
            //Percorre cada middleware.

            if(!is_string($key)) {
                //Se a chave não for uma string.
                $message = "O global middleware <b>$key</b> deve possuir um nome proprio.";
                $exception = new RouterException($message, 303);
                $exception->action("Registro de middlewares globais.");
                throw $exception;
            }


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