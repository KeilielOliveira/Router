<?php 

namespace Router;

use ReflectionMethod;

class Utils {

    //Inforamções uteis.
    private $regexp;

    public function __construct() {
        $this->regexp = [
            'alpha' => '[a-zA-Z-_]+',
            'alpha-upper' => '[A-Z-_]+',
            'alpha-lower' => '[a-z-_]+',
            'numeric' => '[0-9-_]+',
            'alpha-numeric' => '[a-zA-Z0-9-_]+',
            'alpha-numeric-upper' => '[A-Z0-9-_]+',
            'alpha-numeric-lower' => '[a-z0-9-_]+',
        ];
    }

    /**
     * Prepara o $arg retornando informações sobre o mesmo.
     *
     * @param string $arg: A função ou classe a ser preparado.
     * @return array
     */
    public function prepareClassOrFunction(string|callable $arg) {
        $response = [];
        if(is_callable($arg)) {
            //Se for uma função.
            $response = [
                'type' => 'function',
                'function' => $arg
            ];
        }else {
            //Se for uma classe com ou sem metodo.
            if(str_contains($arg, '@')) {
                [$class, $method] = explode('@', $arg);
                $response = [
                    'type' => 'class',
                    'class' => $class,
                    'method' => $method
                ];
            }else {
                $response = [
                    'type' => 'class',
                    'class' => $arg,
                ];
            }
        }

        return $response;
    }

    /**
     * Prepara a rota para a inserção recuperando informações relevantes.
     *
     * @param string $uri: A URI a ser preparada.
     * @return array
     */
    public function prepareRoute(string $uri) {
        $response = ['uri' => $uri];

        //Recupera a parte da URL e da QUERY se existir.
        if(str_contains($uri, ':')) {
            $uri = explode(':', $uri);
            $response['query'] = array_pop($uri);
            $response['url'] = implode(':', $uri);
        }else {
            $response['query'] = null;
            $response['url'] = $uri;
        }

        //Converte a URL em regexp e recupera os nomes das partes indefinidas.
        [$regexp, $params] = $this->converteToRegExp($response['url']);
        $response['regexp'] = $regexp;
        $response['route_params'] = $params;

        return $response;
    }

    /**
     * Converte a URI passada em expressão regular.
     *
     * @param string $url: A URL a ser convertida em expressão regular.
     * @return array
     */
    public function converteToRegExp(string $url) {
        $url = explode('/', $url);
        $regexp = "";
        $params = [];

        //Percorre cada parte da URL.
        foreach ($url as $i => $part) {
            if(preg_match('/^\{.+\}$/', $part)) {
                //Se a parte atual for indefinida.
                preg_match('/\{([a-zA-Z0-9-_]+)(\=(.+))?\}/', $part, $match);
                if(isset($match[3])) {
                    //Se a parte atual possuir uma expressão regular propria.
                    $part = $this->getRegExp($match[3]);
                    $params[$match[1]] = $i;
                    $regexp .= "\/$part";
                }else {
                    //Se não possuir uma expressão regular propria.
                    $params[$match[1]] = $i;
                    $regexp .= "\/[a-zA-Z0-9-_]+";
                }
            }else {
                //Se a parte atual for literal.
                $regexp .= "\/$part";
            }
        }

        $regexp = str_replace('\/\/', '\/', $regexp);

        return [$regexp, $params];
    }

    /**
     * Recupera a expressão regular customizada do parametro.
     *
     * @param string $regexp: A expressão regular.
     * @return string
     */
    public function getRegExp(string $regexp) {
        if(isset($this->regexp[$regexp])) {
            return $this->regexp[$regexp];
        }
        return $regexp;
    }

    /**
     * Recupera os valores dos parametros da rota.
     *
     * @param array $route: A rota que está sendo executada.
     * @return array
     */
    public function getRouteParams(array $route) {
        if(!empty($route['route_params'])) {
            //Recupera a url
            $url = isset($_GET['url']) ? '/' . $_GET['url'] : false;
            
            if(Debug::$isDebugMode && Debug::$url != null) {
                $url = Debug::$url;
            }

            //Se a url for / não há parametros de rota.
            if(!$url) {
                return $route;
            }

            //Percorre os parametros da rota e preenche com os devidos valores.
            $url = explode('/', $url);
            foreach ($route['route_params'] as $key => $value) {
                $route['route_params'][$key] = $url[$value];
            }
        }
        return $route;
    }

    /**
     * Executa a classe ou função passada, passando os parametros $params.
     *
     * @param array $execute: As informações sobre a classe ou função.
     * @param array $params: Parametros que serão passados para a classe ou função.
     * @return mixed
     */
    public function executeClassOrFunction(array $execute, array $params) {
        $type = $execute['type'];

        if($type == 'function') {
            //Se for uma função.
            $function = $execute['function'];
            return $function(...$params);
        }else if($type == 'class') {
            //Se for uma classe com ou sem metodo definido.

            $class = $execute['class'];
            if(isset($execute['method'])) {
                //Se foi passado um metodo para executar.

                $method = $execute['method'];
                $reflection = new ReflectionMethod($class, $method);

                if($reflection->isPublic()) {
                    $class = new $class;
                    return $class->$method(...$params);
                }else if($reflection->isStatic()) {
                    return $class::$method(...$params);
                }

            }else {
                //Se não foi passado nenhum metodo para executar.
                $class = new $class();
                return $class(...$params);
            }

        }

    }


}

?>