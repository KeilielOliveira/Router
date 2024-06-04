<?php 

namespace Router;

use Exception;

class DebugRoutes extends RouterConfig {

    /**
     * Exibe as rotas registradas.
     *
     * @param string $requestMethod
     * @param string $route
     * @return void
     */
    public function print(string $requestMethod = "", string $route = "") {
        try {

            $requestMethod = strtoupper($requestMethod);
            if($requestMethod != "" && isset(self::$registeredRoutes[$requestMethod])) {
                //Se o metodo de requisição http passado for valido.
    
                $registeredRoutes = self::$registeredRoutes[$requestMethod];
                if($route != "" && isset($registeredRoutes[$route])) {
                    //Se a rota passada existir.
                    echo nl2br(print_r($registeredRoutes[$route], true));
                }else if($route == "") {
                    //Se não foi especificado nenhuma rota.
                    echo nl2br(print_r($registeredRoutes, true));
                }else {
                    throw new Exception("Não existe a rota <b>$route</b> no metodo de requisição <b>$requestMethod</b>");
                }
            }else if($requestMethod == "") {
                //Se não foi especificado nenhum metodo de requisição http.
                echo nl2br(print_r(self::$registeredRoutes, true));
            }else {
                throw new Exception("O metodo de requisição HTTP <b>$requestMethod</b> não é valido!");
            }
        }catch(Exception $e) {
            echo "Ocorreu um erro: " . $e->getMessage() . '<br><br>';
        }
    }

}

?>