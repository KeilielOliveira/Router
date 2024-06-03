<?php 

namespace Router;

use Exception;

class RouteMethods {

    private RegisterRoutes $register;

    public function __construct() {
        $this->register = new RegisterRoutes;
    }

    /**
     * Registra parametros adicionais que poderão ser recuperados em middlewares e controladores de rotas.
     *
     * @param array $params
     * @return void
     */
    public function setRouteParams(array $params) {
        $this->register->registerInRoute([
            'route_params' => $params
        ]);
        return;
    }

    /**
     * Registra um controlador para a rota.
     * 
     * @param string|callable $controller
     */
    public function registerController(string | callable $controller) {
        try {
            $routeController = new RouteController;
            if($routeController->isValidController($controller)) {
                //Se o controlador for valido.

                $this->register->registerInRoute([
                    'controller' => $controller
                ]);
                return;
            }
            throw new Exception("O controlador passado não é valido!");
        }catch(Exception $e) {
            echo "Ocorreu um erro: " . $e->getMessage() . '<br><br>';
        }
    }

}

?>