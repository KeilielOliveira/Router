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
        $this->register->registerRouteParams($params);
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

                $this->register->registerRouteController($controller);
                return;
            }
            throw new Exception("O controlador passado não é valido!");
        }catch(Exception $e) {
            echo "Ocorreu um erro: " . $e->getMessage() . '<br><br>';
        }
    }

    /**
     * Registra os before middlewares da rota.
     * 
     * @param string|callable|array $beforeMiddlewares
     * @return void
     */
    public function registerBeforeMiddlewares(string | callable | array $beforeMiddlewares) {
        try {
            $routeMiddlewares = new RouteMiddlewares;
            if($routeMiddlewares->isValidMiddlewares($beforeMiddlewares)) {
                //Se os middlewares forem validos.

                $beforeMiddlewares = is_array($beforeMiddlewares) ? $beforeMiddlewares : [$beforeMiddlewares];
                $this->register->registerRouteMiddlewares($beforeMiddlewares, 'before_middlewares');
                return;
            }
            throw new Exception("Um dos middlewares passados é invalido!");
        }catch(Exception $e) {
            echo "Ocorreu um erro: " . $e->getMessage() . '<br><br>';
        }
    }

        /**
     * Registra os after middlewares da rota.
     * 
     * @param string|callable|array $afterMiddlewares
     * @return void
     */
    public function registerAfterMiddlewares(string | callable | array $afterMiddlewares) {
        try {
            $routeMiddlewares = new RouteMiddlewares;
            if($routeMiddlewares->isValidMiddlewares($afterMiddlewares)) {
                //Se os middlewares forem validos.

                $afterMiddlewares = is_array($afterMiddlewares) ? $afterMiddlewares : [$afterMiddlewares];
                $this->register->registerRouteMiddlewares($afterMiddlewares, 'after_middlewares');
                return;
            }
            throw new Exception("Um dos middlewares passados é invalido!");
        }catch(Exception $e) {
            echo "Ocorreu um erro: " . $e->getMessage() . '<br><br>';
        }
    }

}

?>