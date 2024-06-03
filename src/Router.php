<?php 

namespace Router;

use Exception;

class Router {



    public function get(string $route) {
        try {

            $validator = new RouteValidator();
            if($validator->isValidRoute($route)) {
                //Se a rota for valida.

                
            }
        }catch(Exception $e) {
            echo "Ocorreu um erro: " . $e->getMessage() . '<br><br>';
        }
    }

}

?>