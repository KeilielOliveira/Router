<?php 

namespace Router;

class Debug {

    public static $isDebugMode = false; //Defini se está em modo de teste.
    public static $skipClassOrFunctionValidate = false; //Defini se ira pular a validação dos controladores de rota.

    /**
     * Defini a classe Router para o modo de testes.
     *
     * @return self
     */
    public static function setDebugMode() {
        self::$isDebugMode = true;
        return new Debug();
    }

    /**
     * Defini se a classe Router irá pular a validação dos controladores de rotas.
     *
     * @return self
     */
    public function skipClassOrFunctionValidate() {
        if(self::$isDebugMode) {
            self::$skipClassOrFunctionValidate = true;
        }
        return $this;
    }

    public function showRoutes() {
        if(self::$isDebugMode) {
            foreach (Router::$routes as $method => $routes) {
                echo "<h2>$method</h2><br><br>";
                foreach ($routes as $reference => $config) {
                    echo "<h3>$reference</h3><br>";
                    echo nl2br(print_r($config, true));
                    echo "<br><hr><br>";
                }
            }
        }

        return $this;
    }

    public function getRoute(string $reference, string $method) {
        if(self::$isDebugMode) {
            $method = strtoupper($method);
            if(isset(Router::$routes[$method][$reference])) {
                return Router::$routes[$method][$reference];
            }
        }
        return false;
    } 

}

?>