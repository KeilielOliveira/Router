<?php 

namespace Router;

use Exception;

class Router extends RouterConfig {





    /**
     * Armazena a classe instanciada.
     */
    protected static $class;



    /**
     * Inicia a classe. Mesmo que chame a classe novamente os valores não serão resetados.
     */
    public function __construct($class = __CLASS__) {
        if(empty(self::$registeredRoutes)) {
            self::$registeredRoutes = array(
                'GET' => array(),
                'POST' => array(),
                'PUT' => array(),
                'DELETE' => array(),
                'UPDATE' => array(),
                'PATCH' => array()
            );
        }

        self::$class = $class;
    }

    public function get(string $route, callable $callback) {
        try {

            if(__CLASS__ != self::$class) {
                //Se o metodo está sendo chamado por outra classe.
                throw new Exception("Esse metodo só acessivel pela classe <b>Router</b>!");
            }

            $validator = new ValidateRoute();
            if($validator->isValidRoute($route)) {
                //Se a rota for valida.

                //Prepara a rota para registro.
                $prepare = new PrepareRoute;
                $route = $prepare->prepareRoute($route);

                //Registra a rota.
                $register = new RegisterRoutes;
                $register->registerRoute('GET', $route);

                $callback(new RouteMethods);
            }
        }catch(Exception $e) {
            echo "Ocorreu um erro: " . $e->getMessage() . '<br><br>';
        }
    }

}

?>