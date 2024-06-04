<?php 

namespace Router;

class RouteRequest {

    /**
     * Rota sendo executada como resposta a requisição.
     *
     * @var array
     */
    private array $request;

    /**
     * Salva as informações da rota passada.
     *
     * @param array $request
     */
    public function __construct(array $request) {
        $this->request = $request;
    }

    /**
     * Retorna os parametros de rota.
     *
     * @return array
     */
    public function getRouteParams() {
        return $this->request['route_params'];
    }

    /**
     * Retorna os parametros da url.
     *
     * @return array
     */
    public function getUrlParams() {
        return $this->request['url_params'];
    }

    /**
     * Retorna os parametros GET que a rota deve ter.
     *
     * @return void
     */
    public function getQueryParams() {
        return $this->request['query_params'];
    }

    /**
     * Retorna o metodo http da requisição.
     *
     * @return void
     */
    public function getRequestMethod() {
        return $_SERVER['REQUEST_METHOD'];
    }
    
}

?>