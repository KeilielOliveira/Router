<?php 

namespace Router;

use ReflectionMethod;

class Validate {

    /**
     * Verifica se a URI passada é valida.
     *
     * @param string $uri: A URI a ser validada.
     * @return boolean
     */
    public function isValidUri(string $uri) {
        $p = "([a-zA-Z0-9-_]+|\{[a-zA-Z0-9-_]+(\=.+)?\})";
        $i = "\:[a-zA-Z0-9-_]+(\&[a-zA-Z0-9-_]+)*";
        $regexp = "/^\/($p(\/$p)*)?($i)?$/U";

        //Se a URI for valida.
        if(preg_match($regexp, $uri)) {
            return true;
        }

        return false;
    }

    /**
     * Valida se o $arg é uma classe (com ou sem metodo incluso) ou função valida.
     *
     * @param string|callable $arg: O parametro a ser validado.
     * @return boolean
     */
    public function isValidClassOrFunction(string|callable $arg) {
        if(Debug::$skipClassOrFunctionValidate && Debug::$isDebugMode) {
            return true;
        }else if(is_callable($arg)) {
            //Se for uma função anonima.
            return true;
        }else if(is_string($arg)) {

            //Recupera a classe e o metodo na string.
            if(str_contains($arg, '@')) {
                [$class, $method] = explode('@', $arg);
            }else {
                $class = $arg;
                $method = '__construct';
            }

            if(class_exists($class) && method_exists($class, $method)) {
                //Se a classe exisitir e o metodo existir na classe.
                if($this->isValidMethod($class, $method)) {
                    //Se o metodo for publico ou estatico.
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Verifica se o metodo passado é publico ou estatico.
     *
     * @param string $class: A classe que contem o metodo.
     * @param string $method: O metodo a ser validado.
     * @return boolean
     */
    public function isValidMethod(string $class, string $method) {
        $reflection = new ReflectionMethod($class, $method);
        if($reflection->isPublic() || $reflection->isStatic()) {
            //Se for valido.
            return true;
        } 

        return false;
    }

    /**
     * Verifica se uma rota existe.
     *
     * @param string $uri: A URL da rota.
     * @param string $method: O metodo de requisição HTTP da rota.
     * @return bool
     */
    public function routeExists(string $uri, string $method) {
        $method = strtoupper($method);
        if(isset(Router::$routes[$method])) {
            foreach (Router::$routes[$method] as $key => $route) {
                if($uri == $route['uri']) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Verifica se uma rota existe dentro do grupo.
     *
     * @param string $uri: A URL da rota.
     * @param string $method: O metodo de requisição HTTP da rota.
     * @return bool
     */
    public function routeExistsInGroup(string $uri, string $method) {
        $method = strtoupper($method);
        if(isset(Group::$routes[$method])) {
            foreach (Group::$routes[$method] as $key => $route) {
                if($uri == $route['uri']) {
                    return true;
                }
            }
        }
        return false;
    }


    public function validateRoute() {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $url = isset($_GET['url']) ? '/' . $_GET['url'] : '/'; 

        if(Debug::$isDebugMode && Debug::$url != null) {
            $url = Debug::$url;
        }

        foreach (Router::$routes as $method => $routes) {
            if(Debug::$isDebugMode && Debug::$requestMethod != null) {
                $requestMethod = Debug::$requestMethod;
            }
            if($requestMethod == $method) {
                foreach ($routes as $route => $config) {
                    $regexp = $config['regexp'];
                    if(preg_match("/^$regexp$/", $url)) {
                        if($this->validateGetParams($config['query'])) {
                            return Router::$routes[$method][$route];
                        }
                    }
                }
            }
        }

        return false;
    }

    /**
     * Valida os parametros GET da rota.
     *
     * @param string|null $query: A query com os parametros GET da rota.
     * @return bool
     */
    public function validateGetParams(string|null $query) {
        //Se a query for NULL.
        if($query === null) {
            return true;
        }

        //Percorre todos os parametros GET da query.
        $params = explode('&', $query);
        foreach ($params as $key => $param) {
            //Se não existir um dos parametros da query.
            if(!isset($_GET[$param])) {
                return false;
            }
        }
        return true;
    }

}

?>