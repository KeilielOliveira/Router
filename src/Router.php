<?php 

namespace Router;

use Exception;

class Router {

    public static $routes = []; //Armazena todas as rotas e suas informações.
    public static $content; //O conteudo da rota atual.

    /**
     * Registra uma rota para o metodo de requisição HTTP GET.
     *
     * @param string $route: A URL da rota.
     * @param callable $callback: Funcção de callback onde todas as configurações da rota são definidas.
     * @return bool
     */
    public static function get(string $route, callable $callback) {
        try {
            $validate = new Validate();
            if(is_callable($callback) && $validate->isValidUri($route)) {
                //Se o callback for uma função e a URI for valida.
                $route = $callback(new Methods($route));
                self::addRoute($route, 'GET');
                return true;
            }
            throw is_callable($callback) ? new Exception("A rota <b>$route</b> não é valida!")
            : new Exception("O callback deve ser uma função!");
        }catch(Exception $e) {
            echo $e->getMessage();
            echo '<br><hr><br>';
        }
    }

    /**
     * Registra uma rota para o metodo de requisição HTTP POST.
     *
     * @param string $route: A URL da rota.
     * @param callable $callback: Funcção de callback onde todas as configurações da rota são definidas.
     * @return bool
     */
    public static function post(string $route, callable $callback) {
        try {
            $validate = new Validate();
            if(is_callable($callback) && $validate->isValidUri($route)) {
                //Se o callback for uma função e a URI for valida.
                $route = $callback(new Methods($route));
                self::addRoute($route, 'POST');
                return true;
            }
            throw is_callable($callback) ? new Exception("A rota <b>$route</b> não é valida!")
            : new Exception("O callback deve ser uma função!");
        }catch(Exception $e) {
            echo $e->getMessage();
            echo '<br><hr><br>';
        }
    }

    /**
     * Registra uma rota para o metodo de requisição HTTP PUT.
     *
     * @param string $route: A URL da rota.
     * @param callable $callback: Funcção de callback onde todas as configurações da rota são definidas.
     * @return bool
     */
    public static function put(string $route, callable $callback) {
        try {
            $validate = new Validate();
            if(is_callable($callback) && $validate->isValidUri($route)) {
                //Se o callback for uma função e a URI for valida.
                $route = $callback(new Methods($route));
                self::addRoute($route, 'PUT');
                return true;
            }
            throw is_callable($callback) ? new Exception("A rota <b>$route</b> não é valida!")
            : new Exception("O callback deve ser uma função!");
        }catch(Exception $e) {
            echo $e->getMessage();
            echo '<br><hr><br>';
        }
    }

    /**
     * Registra uma rota para o metodo de requisição HTTP DELETE.
     *
     * @param string $route: A URL da rota.
     * @param callable $callback: Funcção de callback onde todas as configurações da rota são definidas.
     * @return bool
     */
    public static function delete(string $route, callable $callback) {
        try {
            $validate = new Validate();
            if(is_callable($callback) && $validate->isValidUri($route)) {
                //Se o callback for uma função e a URI for valida.
                $route = $callback(new Methods($route));
                self::addRoute($route, 'DELETE');
                return true;
            }
            throw is_callable($callback) ? new Exception("A rota <b>$route</b> não é valida!")
            : new Exception("O callback deve ser uma função!");
        }catch(Exception $e) {
            echo $e->getMessage();
            echo '<br><hr><br>';
        }
    }

    /**
     * Registra uma rota para o metodo de requisição HTTP PATCH.
     *
     * @param string $route: A URL da rota.
     * @param callable $callback: Funcção de callback onde todas as configurações da rota são definidas.
     * @return bool
     */
    public static function patch(string $route, callable $callback) {
        try {
            $validate = new Validate();
            if(is_callable($callback) && $validate->isValidUri($route)) {
                //Se o callback for uma função e a URI for valida.
                $route = $callback(new Methods($route));
                self::addRoute($route, 'PATCH');
                return true;
            }
            throw is_callable($callback) ? new Exception("A rota <b>$route</b> não é valida!")
            : new Exception("O callback deve ser uma função!");
        }catch(Exception $e) {
            echo $e->getMessage();
            echo '<br><hr><br>';
        }
    }

    /**
     * Registra um grupo de rotas.
     *
     * @param callable $callback: Função de callback onde as rotas do grupo são registradas.
     * @return bool
     */
    public static function group(callable $callback) {
        try {
            if(is_callable($callback)) {
                $callback(new Group);
                return true;
            }
        }catch(Exception $e) {
            echo $e->getMessage();
            echo '<br><hr><br>';
        }
    }

    public static function handleRoutes() {
        $validate = new Validate();
        $utils = new Utils();
        $middlewares = new Middlewares();

        //Procura uma rota valida.
        $route = $validate->validateRoute();
        if($route) {
            $response = new Response();
            $route = $utils->getRouteParams($route);
            if($middlewares->executeMiddlewares($route['middlewares']['before'], [$route, $response])) {
                $utils->executeClassOrFunction($route['controller'], [$route, $response]);
                if($middlewares->executeMiddlewares($route['middlewares']['after'], [$route, $response])) {
                    $response->send();
                }
            }
        }

    }

    /**
     * Registra a rota.
     *
     * @param array $route: As informações da rota.
     * @param string $method: O metodo de requisição HTTP da rota.
     * @return void
     */
    protected static function addRoute(array $route, string $method) {
        $reference = isset($route['reference']) ? $route['reference'] : $route['uri'];
        $validate = new Validate();

        //Se a rota já não tiver sido registrada.
        if(!$validate->routeExists($route['uri'], $method)) {
            self::$routes[$method][$reference] = $route;
            return;
        }
        throw new Exception("A rota <b>$route[uri]</b> já foi registrada!");
    }

}

?>