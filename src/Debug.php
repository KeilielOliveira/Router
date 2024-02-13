<?php 

namespace Router;

class Debug {

    public static $isDebugMode = false; //Defini se está em modo de teste.
    public static $skipClassOrFunctionValidate = false; //Defini se ira pular a validação dos controladores de rota.

    public static $url, $requestMethod;

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

    /**
     * Defini uma URL ficticia para testes.
     *
     * @param string $url: A url
     * @return self
     */
    public function setRoute(string $url, string $requestMethod) {
        if(self::$isDebugMode) {
            self::$url = $url;
            self::$requestMethod = strtoupper($requestMethod);
        }
        return $this;
    }

    /**
     * Exibe todas as rotas registradas.
     *
     * @return self
     */
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

    /**
     * Exibe uma rota especifica.
     *
     * @param string $reference: A referencia da rota dentro do array de rotas registradas.
     * @param string $method: O metodo de requisição HTTP da rota.
     * @return array|bool
     */
    public function getRoute(string $reference, string $method) {
        if(self::$isDebugMode) {
            $method = strtoupper($method);
            if(isset(Router::$routes[$method][$reference])) {
                return Router::$routes[$method][$reference];
            }
        }
        return false;
    } 

    /**
     * Mostra todas as rotas registradas de um grupo.
     *
     * @return self
     */
    public function showGroup() {
        if(self::$isDebugMode) {
            foreach (Group::$routes as $method => $routes) {
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

}

?>