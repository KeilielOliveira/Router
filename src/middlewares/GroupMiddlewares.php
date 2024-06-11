<?php 

namespace Router\Middlewares;

use Router\RouterConfig;
use Router\RouterException;
use ReflectionMethod;

class GroupMiddlewares extends RouterConfig {

    /**
     * Armazena o nome do grupo.
     *
     * @var string
     */
    private string $group;

    /**
     * Defini o tipo dos middlewares.
     *
     * @var string
     */
    private string $middlewareType;

    public function __construct(string $group, string $middlewareType) {
        $this->group = $group;
        $this->middlewareType = $middlewareType;
    }

    /**
     * Registra middlewares para um grupo.
     * 
     * @return void
     */
    public function registerGroupMiddlewares(string | array | callable $groupMiddlewares) : void {
        if($this->isValidMiddlewares($groupMiddlewares)) {
            //Se os middlewares forem validos.

            //Informações do grupo.
            $group = $this->group;
            $middlewareType = $this->middlewareType . '_middlewares';

            //Registro dos middlewares.
            $groupMiddlewares = is_array($groupMiddlewares) ? $groupMiddlewares : [$groupMiddlewares];
            $middlewares = self::$groups[$group]['middlewares'][$middlewareType];
            $groupMiddlewares = array_merge($middlewares, $groupMiddlewares);
            self::$groups[$group]['middlewares'][$middlewareType] = $groupMiddlewares;
            return;
        }

        //Lança o erro.
        $group = $this->group;
        $message = "Um dos middlewares de grupo do grupo <b>$group</b> é invalido.";
        throw new RouterException($message, 403);
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