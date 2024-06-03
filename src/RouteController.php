<?php 

namespace Router;

use ReflectionMethod;

class RouteController extends RouterConfig {

    /**
     * Verifica se o controlodaor passado é valido.
     * 
     * @param string|callable $controller
     * @return bool
     */
    public function isValidController(string | callable $controller) {
        $skipControllerValidation = self::$routeControllerConfig['skip_controller_validation'];
        if(is_callable($controller) || $skipControllerValidation) {
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