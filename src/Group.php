<?php 

namespace Router;

use Exception;

class Group {

    public static $routes; //Armazena todas as rotas do grupo.

    //Instancias de classes.
    private $validate, $utils;

    //Informações da classe.
    private $prefix, $group;

    /**
     * Inicializa variaveis da classe.
     */
    public function __construct() {
        self::$routes = [];

        $this->validate = new Validate();
        $this->utils = new Utils();

        $this->prefix = '';
        $this->group = [];
    }

    /**
     * Registra uma rota para o metodo de requisição HTTP GET.
     *
     * @param string $route: A URL da rota.
     * @param callable $callback: Funcção de callback onde todas as configurações da rota são definidas.
     * @return bool
     */
    public function get(string $route, callable $callback) {
        try {
            $route = $this->prefix === null ? $route : $this->prefix . $route;
            if(is_callable($callback) && $this->validate->isValidUri($route)) {
                //Se o callback for uma função e a URI for valida.
                $route = $callback(new Methods($route));
                $this->addRoute($route, 'GET');
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
    public function post(string $route, callable $callback) {
        try {
            $route = $this->prefix === null ? $route : $this->prefix . $route;
            if(is_callable($callback) && $this->validate->isValidUri($route)) {
                //Se o callback for uma função e a URI for valida.
                $route = $callback(new Methods($route));
                $this->addRoute($route, 'POST');
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
    public function put(string $route, callable $callback) {
        try {
            $route = $this->prefix === null ? $route : $this->prefix . $route;
            if(is_callable($callback) && $this->validate->isValidUri($route)) {
                //Se o callback for uma função e a URI for valida.
                $route = $callback(new Methods($route));
                $this->addRoute($route, 'PUT');
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
    public function delete(string $route, callable $callback) {
        try {
            $route = $this->prefix === null ? $route : $this->prefix . $route;
            if(is_callable($callback) && $this->validate->isValidUri($route)) {
                //Se o callback for uma função e a URI for valida.
                $route = $callback(new Methods($route));
                $this->addRoute($route, 'DELETE');
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
    public function patch(string $route, callable $callback) {
        try {
            $route = $this->prefix === null ? $route : $this->prefix . $route;
            if(is_callable($callback) && $this->validate->isValidUri($route)) {
                //Se o callback for uma função e a URI for valida.
                $route = $callback(new Methods($route));
                $this->addRoute($route, 'PATCH');
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
     * Defini um prefixo de rota para o grupo.
     *
     * @param string $prefix: O prefixo das rotas do grupo.
     * @return self
     */
    public function prefix(string $prefix) {
        try {
            if($this->validate->isValidUri($prefix) && $this->prefix == '') {
                $this->prefix = $prefix;
                return $this;
            }

            throw new Exception("O prefixo <b>$prefix</b> não é valido!");
        }catch(Exception $e) {
            echo $e->getMessage();
            echo '<br><hr><br>';
        }
    }

    /**
     * Defini parametros que serão passados para todas as rotas do grupo.
     *
     * @param array $params: Os parametros.
     * @return self
     */
    public function groupParams(array $params) {
        if(is_array($params)) {
            $this->group['params'] = $params;
            return $this;
        }
    }

    /**
     * Registra um controlador que será adicionado a todas as rotas do grupo.
     *
     * @param string|callable $controller: O controlador do grupo.
     * @return self
     */
    public function groupController(string|callable $controller) {
        try {
            if($this->validate->isValidClassOrFunction($controller)) {
                $controller = $this->utils->prepareClassOrFunction($controller);
                $this->group['group_controller'] = $controller;
                return $this;
            }

            throw new Exception("O controlador de grupo é invalido!");
        }catch(Exception $e) {
            echo $e->getMessage();
            echo '<br><hr><br>'; 
        }
    }

    /**
     * Registra middlewares para todas as rotas do grupo.
     *
     * @param callable $callback: Função de callback que recebe a instancia da classe de registro de middlewares.
     * @return self
     */
    public function groupMiddlewares(callable $callback) {
        try {
            if(is_callable($callback)) {
                $middlewares = $callback(new Middlewares());
                $this->group['middlewares'] = $middlewares;
                return $this;
            }

            throw new Exception('O callback deve ser uma função!');
        }catch(Exception $e) {
            echo $e->getMessage();
            echo '<br><hr><br>';
        }
    }

    /**
     * Adiciona as rotas do grupo nas rotas registradas.
     *
     * @return void
     */
    public function return() {
        try {
            $this->addGroupParams();
            $this->addGroupController();
            $this->addGroupMiddlewares();

            Router::$routes = array_merge_recursive(Router::$routes, self::$routes);
        }catch(Exception $e) {
            echo $e->getMessage();
            echo '<br><hr><br>'; 
        }
    }

    /**
     * Mescla os parametros do grupo com os parametros das rotas.
     *
     * @return void
     */
    private function addGroupParams() {
        if(isset($this->group['params'])) {
            foreach (self::$routes as $method => $routes) {
                foreach ($routes as $route => $config) {
                    if(isset($config['params'])) {
                        $params = array_merge($this->group['params'], $config['params']);
                    }else {
                        $params = $this->group['params'];
                    }
                    self::$routes[$method][$route]['params'] = $params;
                }
            }
        }
    }

    /**
     * Adiciona o controlador do grupo a todas as rotas do grupo.
     *
     * @return void
     */
    private function addGroupController() {
        if(isset($this->group['group_controller'])) {
            foreach (self::$routes as $method => $routes) {
                foreach ($routes as $route => $config) {
                    self::$routes[$method][$route]['controller'] = $this->group['group_controller'];
                }
            }
        }
        return;
    }

    /**
     * Adiciona os middlewares do grupo a todas as rotas.
     *
     * @return void
     */
    private function addGroupMiddlewares() {
        if(isset($this->group['middlewares'])) {
            //Recupera os middlewares do grupo.
            $before = $this->group['middlewares']['before'];
            $after = $this->group['middlewares']['after'];

            //Percorre todas as rotas do grupo.
            foreach (self::$routes as $method => $routes) {
                foreach ($routes as $route => $config) {
                    //Junta os middlewares da rota com os middlewares do grupo.
                    if(isset($config['middlewares'])) {
                        $before = array_merge($before, $config['middlewares']['before']);
                        $after = array_merge($after, $config['middlewares']['after']);
                    }

                    //Atualiza a rota.
                    self::$routes[$method][$route]['middlewares'] = [
                        'before' => $before,
                        'after' => $after
                    ];
                }
            }
        }
        return;
    }

    /**
     * Registra as rotas dentro das rotas de grupo.
     *
     * @param array $route: As informações da rota.
     * @param string $method: O metodo de requisição HTTP da rota.
     * @return void
     */
    protected function addRoute(array $route, string $method) {
        $reference = isset($route['reference']) ? $route['reference'] : $route['uri'];
        $validate = new Validate();

        //Se a rota já não tiver sido registrada.
        if(!$validate->routeExistsInGroup($route['uri'], $method)) {
            self::$routes[$method][$reference] = $route;
            return;
        }
        throw new Exception("A rota <b>$route[uri]</b> já foi registrada!");
    }

}

?>