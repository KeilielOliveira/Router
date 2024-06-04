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

    /**
     * Registra uma rota do tipo GET.
     *
     * @param string $route
     * @param callable $callback
     * @return void
     */
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

    /**
     * Registra uma rota do tipo POST.
     *
     * @param string $route
     * @param callable $callback
     * @return void
     */
    public function post(string $route, callable $callback) {
        try {
            $validator = new ValidateRoute();
            if($validator->isValidRoute($route)) {
                //Se a rota for valida.

                //Prepara a rota para registro.
                $prepare = new PrepareRoute;
                $route = $prepare->prepareRoute($route);

                //Registra a rota.
                $register = new RegisterRoutes;
                $register->registerRoute('POST', $route);

                $callback(new RouteMethods);
            }
        }catch(Exception $e) {
            echo "Ocorreu um erro: " . $e->getMessage() . '<br><br>';
        }
    }

    /**
     * Registra uma rota do tipo PUT.
     *
     * @param string $route
     * @param callable $callback
     * @return void
     */
    public function put(string $route, callable $callback) {
        try {
            $validator = new ValidateRoute();
            if($validator->isValidRoute($route)) {
                //Se a rota for valida.

                //Prepara a rota para registro.
                $prepare = new PrepareRoute;
                $route = $prepare->prepareRoute($route);

                //Registra a rota.
                $register = new RegisterRoutes;
                $register->registerRoute('PUT', $route);

                $callback(new RouteMethods);
            }
        }catch(Exception $e) {
            echo "Ocorreu um erro: " . $e->getMessage() . '<br><br>';
        }
    }

        /**
     * Registra uma rota do tipo DELETE.
     *
     * @param string $route
     * @param callable $callback
     * @return void
     */
    public function delete(string $route, callable $callback) {
        try {
            $validator = new ValidateRoute();
            if($validator->isValidRoute($route)) {
                //Se a rota for valida.

                //Prepara a rota para registro.
                $prepare = new PrepareRoute;
                $route = $prepare->prepareRoute($route);

                //Registra a rota.
                $register = new RegisterRoutes;
                $register->registerRoute('DELETE', $route);

                $callback(new RouteMethods);
            }
        }catch(Exception $e) {
            echo "Ocorreu um erro: " . $e->getMessage() . '<br><br>';
        }
    }

    /**
     * Registra uma rota do tipo UPDATE.
     *
     * @param string $route
     * @param callable $callback
     * @return void
     */
    public function update(string $route, callable $callback) {
        try {
            $validator = new ValidateRoute();
            if($validator->isValidRoute($route)) {
                //Se a rota for valida.

                //Prepara a rota para registro.
                $prepare = new PrepareRoute;
                $route = $prepare->prepareRoute($route);

                //Registra a rota.
                $register = new RegisterRoutes;
                $register->registerRoute('UPDATE', $route);

                $callback(new RouteMethods);
            }
        }catch(Exception $e) {
            echo "Ocorreu um erro: " . $e->getMessage() . '<br><br>';
        }
    }

    /**
     * Registra uma rota do tipo PATCH.
     *
     * @param string $route
     * @param callable $callback
     * @return void
     */
    public function patch(string $route, callable $callback) {
        try {
            $validator = new ValidateRoute();
            if($validator->isValidRoute($route)) {
                //Se a rota for valida.

                //Prepara a rota para registro.
                $prepare = new PrepareRoute;
                $route = $prepare->prepareRoute($route);

                //Registra a rota.
                $register = new RegisterRoutes;
                $register->registerRoute('PATCH', $route);

                $callback(new RouteMethods);
            }
        }catch(Exception $e) {
            echo "Ocorreu um erro: " . $e->getMessage() . '<br><br>';
        }
    }

    /**
     * Lida com o registro de erros customizados.
     *
     * @param callable $callback
     * @return void
     */
    public function error(callable $callback) {
        $callback(new RouterErrors);
        return;
    }

    /**
     * Lida com a execução das rotas.
     *
     * @return void
     */
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

            if(isset(self::$errors[404])) {
                //Se o erro 404 foi registrado.
                $error = new RouterErrors;
                $error->executeError(404);
                return;
            }

            echo "Erro 404: Pagina não encontrada!";
        }
    }

}

?>