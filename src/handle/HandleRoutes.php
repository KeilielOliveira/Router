<?php 

namespace Router\Handle;

use Router\Controller\RouteController;
use Router\Middlewares\RouteMiddlewares;
use Router\RouterConfig;

class HandleRoutes extends RouterConfig {

    private string $url; //Url atual da requisição.
    private string $requestMethod; //Metodo de requisição http atual da requisição.
    private RouteMiddlewares $middlewares; //Instancia da classe que lida com middlewares.
    private RouteController $controller; //Instancia da classe que lida com controladores.

    /**
     * Lida com a execução das rotas.
     */
    public function __construct() {
        $this->init();
        $route = $this->findRoute();
        if(is_array($route)) {
            //Se uma rota foi encontrada.
            $route = $this->prepareRoute($route);

            //Recupera os middlewares e o controlador.
            $beforeMiddlewares = $this->getMiddlewares($route, 'before');
            $afterMiddlewares = $this->getMiddlewares($route, 'after');
            $controller = $route['controller'];

            //Defini o CSRF token da rota.
            $this->setCsrfToken();

            //Instancias de classes.
            $request = new Request($route);
            $response = new Response($request);

            $result = $this->middlewares->executeMiddlewares($beforeMiddlewares, [$request, $response]);
            if($result) {
                $result = $this->controller->executeController($controller, [$request, $response]);
                if($result !== false) {
                    $this->middlewares->executeMiddlewares($afterMiddlewares, [$request, $response]);
                }
            } 

            //Envia a resposta.
            $response->send();
        }

    }

    /**
     * Inicia variaveis uteis.
     *
     * @return void
     */
    private function init(): void {
        $url = isset($_GET['url']) ? '/' . $_GET['url'] : '';
        $requestMethod = $_SERVER['REQUEST_METHOD'];    

        $this->url = $url;
        $this->requestMethod = $requestMethod;

        $this->middlewares = new RouteMiddlewares;
        $this->controller = new RouteController;
    }

    /**
     * Procura uma rota que bata com a requisição atual.
     *
     * @return void
     */
    private function findRoute() : array | bool {
        $registeredRoutes = self::$registeredRoutes[$this->requestMethod];
        foreach($registeredRoutes as $route => $config) {
            //Percorre cada rota.

            if($this->validateRoute($config)) {
                //Se a rota bater.
                return $config;
            }
        }
        return false;
    }

    /**
     * Verifica se a rota bate com a requisição atual.
     *
     * @param array $route
     * @return boolean
     */
    private function validateRoute(array $route) : bool {
        $regexp = $route['url_regexp'];
        if(preg_match($regexp, $this->url)) {
            //Se a url bater com a da rota.
            $queryParams = $route['get_params'];
            foreach($queryParams as $key => $param) {
                //Percorre cada parametro da query da rota.
                if(!isset($_GET[$param])) {
                    //Se o parametro não existir.
                    return false;
                }
            }
            return true;
        }
        return false;
    }

    /**
     * Faz os ultimos preparativos para a execução da rota.
     *
     * @param array $route
     * @return array
     */
    private function prepareRoute(array $route) : array {
        $routeParams = $route['url_hidden_params'];
        $urlParts = explode('/', $this->url);
        array_shift($urlParts);
        
        foreach($routeParams as $key => $index) {
            //Percorre cada parte da url.
            $route['url_hidden_params'][$key] = $urlParts[$index];
        }
        return $route;
    }

    /**
     * Defini o CSRF token da rota.
     *
     * @param integer $bytes
     * @return void
     */
    private function setCsrfToken(int $bytes = 64) : void {
        $token = "";
        for($i = 0; $i < $bytes; $i++) {
            $token .= self::$chars[mt_rand(0, strlen(self::$chars)) - 1];
        }

        $_SESSION['csrf_route_token'] = $token;
        return;
    }

    private function getMiddlewares(array $route, string $middlewaresType) : array {
        $middlewaresType .= '_middlewares';
        $routeMiddlewares = $route['middlewares'][$middlewaresType];
        if($route['group'] !== null) {
            //Se a rota foi registrada em um grupo.
            $group = $route['group'];
            $groupMiddlewares = self::$groups[$group]['middlewares'][$middlewaresType];
            $routeMiddlewares = array_merge($routeMiddlewares, $groupMiddlewares);
        }
        return $routeMiddlewares;
    }

}

?>