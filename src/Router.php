<?php 

namespace Router;

use Exception;

class Router extends RouterConfig {

    /**
     * Inicia a classe. Mesmo que chame a classe novamente os valores não serão resetados.
     */
    public function __construct() {
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
    }

    public function get(string $route, callable $callback) {
        try {
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

    public function handleRoutes() {
        $validator = new ValidateRoute();
        $controller = new RouteController;
        $middlewares = new RouteMiddlewares;
        $prepare = new PrepareRoute;

        $route = $validator->validateRoute();
        if($route && $validator->validateQueryParams($route['query_params'])) {
            //Se uma rota bater com os dados da requisição atual.

            $route = $prepare->getUrlParamsValues($route);
            $prepare->setCsrfToken();
            $request = new RouteRequest($route);
            $response = new RequestResponse;
        
            if($middlewares->executeMiddlewares($route['middlewares']['before_middlewares'], [$request, $response])) {
                //Se os middlewares não encerrarem a execução.
                $controller->executeController($route['controller'], [$request, $response]);
                $middlewares->executeMiddlewares($route['middlewares']['after_middlewares'], [$request, $response]);
            }
            $response->view();
        }else {
            echo "Erro 404: Pagina não encontrada!";
        }
    }

}

?>