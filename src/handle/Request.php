<?php 

namespace Router\Handle;

class Request {

    /**
     * Informações da rota requerida.
     *
     * @var array
     */
    protected static array $route;

    /**
     * Salava as informações relevantes da requisição.
     */
    public function __construct(array $route) {
        self::$route = $route;
    }

    /**
     * Recupera o metodo de requisição http da rota.
     *
     * @return string
     */
    public function requestMethod() : string {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        return $requestMethod;
    }

    /**
     * Recupera a url completa da pagina.
     *
     * @return string
     */
    public function url() : string {
        $url = isset($_GET['url']) ? $_GET['url'] : '';
        $url = $_SERVER["REQUEST_SCHEME"] . ':\\\\' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] . $url;
        return $url;
    }

    /**
     * Recupera o id do dispositovo que fez a requisição.
     *
     * @return string
     */
    public function requestId() : string {
        $id = $_SERVER['REMOTE_ADDR'];
        return $id;
    } 

    /**
     * Recupera os parametros ocultos da url da rota.
     *
     * @return array
     */
    public function urlHiddenParams(string | null $item = null) : array | string {
        $params = self::$route['url_hidden_params'];
        if($item !== null) {
            $params = $params[$item];
        }
        return $params;
    }

    /**
     * Retorna os parametros GET da rota.
     *
     * @return array
     */
    public function queryParams() : array {
        $params = self::$route['get_params'];
        return $params;
    }

    /**
     * Recupera os parametros customizados da rota.
     */
    public function params(string | null $item = null) : mixed {
        $params = self::$route['route_params'];
        if($item !== null) {
            $params = $params[$item];
        }
        return $params;
    }

    /**
     * Recupera o CSRF token da rota.
     *
     * @return string
     */
    public function csrfToken() : string {
        return $_SESSION['csrf_route_token'];
    }
}

?>