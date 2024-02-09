<?php 

namespace Router;

use Exception;

class Router {


    public static function get(string $route, callable $callback) {
        try {
            $validate = new Validate();
            if(is_callable($callback) && $validate->isValidUri($route)) {
                //Se o callback for uma função e a URI for valida.
                $callback(new Methods($route));
                return true;
            }
        }catch(Exception $e) {

        }
    }

}

?>