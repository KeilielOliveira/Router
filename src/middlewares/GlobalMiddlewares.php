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
    public function registerGlobalMiddlewares(string $middlewaresType, array $globalMiddlewares) : void {
        if($this->isValidMiddlewares($globalMiddlewares)) {
            //Se os middlewares forem validos.
            if($middlewaresType == 'before' || $middlewaresType == 'after') {
                //Se o tipo do middleware for valido.

                $middlewaresType .= '_middlewares';
                $middlewares = self::$globalMiddlewares[$middlewaresType];
                $globalMiddlewares = array_merge($middlewares, $globalMiddlewares);
                self::$globalMiddlewares[$middlewaresType] = $globalMiddlewares;
            }
            return;
        }

        $message = "Um dos middlewares passados é invalido.";
        $exception = new RouterException($message, 403);
        $exception->additionalContent([
            'main action' => 'registro de middlewares globais',
            'action' => 'validação de middlewares',
        ]);
        throw $exception;
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