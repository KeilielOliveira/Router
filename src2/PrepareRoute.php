<?php 

namespace Router;

class PrepareRoute {

    /**
     * Prepara a rota para registro.
     *
     * @param string $route
     * @return void
     */
    public function prepareRoute(string $route) {
        $url = $this->getUrl($route);
        $urlRegexp = $this->convertUrlToRegexp($url);
        $urlParams = $this->getUrlParams($url);
        $queryParams = $this->getQueryParams($route);
        return [
            'url' => $url,
            'url_regexp' => $urlRegexp,
            'url_params' => $urlParams,
            'query_params' => $queryParams
        ];
    }

    /**
     * Recupera os valores dos parametros ocultos da rota.
     *
     * @param array $route
     * @return array
     */
    public function getUrlParamsValues(array $route) {
        $url = isset($_GET['url']) ? $_GET['url'] : null;
        if($url != null) {
            //Se a url não for vazia.

            $urlParts = explode('/', $url);
            $urlParams = $route['url_params'];
            foreach ($urlParams as $key => $index) {
                //Percorre cada parametro da url.

                $route['url_params'][$key] = $urlParts[$index];
            }
        }
        return $route;
    }

    /**
     * Defini um CSRF token para a rota e o salva em uma seção.
     *
     * @return void
     */
    public function setCsrfToken() {
        $csrfToken = bin2hex(random_bytes(32));
        $_SESSION['csrf_route_token'] = $csrfToken;
        return;
    }

    /**
     * Pega a url da rota passada.
     *
     * @param string $route
     * @return string
     */
    private function getUrl(string $route) {
        if(str_contains($route, ':')) {
            //Se a rota possui uma query string.
            return explode(':', $route)[0];
        }
        return $route;
    }

    /**
     * Converte a url passada em uma expressão regular valida.
     *
     * @param string $url
     * @return string
     */
    private function convertUrlToRegexp(string $url) {

    }

    /**
     * Recupera as partes ocultas da url como parâmetros.
     *
     * @param string $url
     * @return array
     */
    private function getUrlParams(string $url) {

    }

    /**
     * Recupera os parametros GET da query da rota.
     *
     * @param string $url
     * @return null|array
     */
    private function getQueryParams(string $url) {
        if(str_contains($url, ':')) {
            //Se a url possui uma query string.
            $query = explode(':', $url)[1];
            $queryParams = explode('&', $query);
            return $queryParams;
        }
        return null;
    }

}

?>