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

    }

    /**
     * Executa o controlador passado, passando o $args como parametros.
     * 
     * @param string|callable $controller
     * @param array $args
     * @return mixed
     */
    public function executeController(string | callable $controller, array $args) {

        if(is_callable($controller)) {
            //Se for uma função.
            return $controller(...$args);
        }else {
            //Se o controlador for uma classe.

            if(str_contains($controller, '@')) {
                //Se foi passado um metodo especifico.
                [$class, $method] = explode('@', $controller);
            }else {
                //Se não foi passado um metodo especifico.
                [$class, $method] = [$controller, 'controller'];
            }

            $reflectionMethod = new ReflectionMethod($class, $method);
            if($reflectionMethod->isPublic()) {
                //Se o metodo for publico.

                $controller = new $class;
                return $controller->$method(...$args);
            }else {
                //Se o metodo for estatico.
                return $class::$method(...$args);
            }
        }
    }

}

?>