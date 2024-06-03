<?php 

namespace Router;

use Exception;

class Router {



    public function get(string $route) {
        try {

            $validator = new RouterValidator();
            if($validator->isValidRoute($route)) {
                //Se a rota for valida.

                $prepare = new PrepareRoute;
                $route = $prepare->prepareRoute($route);
            }
        }catch(Exception $e) {
            echo "Ocorreu um erro: " . $e->getMessage() . '<br><br>';
        }
    }

}

?>