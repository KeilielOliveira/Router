<?php 

namespace Router\Routes;

use Router\RouterConfig;

class PrepareRoute extends RouterConfig {

    /**
     * Prepara as informações da rota para registro.
     *
     * @param string $route
     * @return array
     */
    public function prepareRoute(string $route) : array {
        $routeUrl = $this->getRouteUrl($route);
        $urlRegexp = $this->convertUrlToRegexp($routeUrl);
        $urlParams = $this->getUrlHiddenParams($routeUrl);
        $queryParams = $this->getRouteQueryParams($route);

        return [
            'route_url' => $routeUrl,
            'url_regexp' => $urlRegexp,
            'url_hidden_params' => $urlParams,
            'get_params' => $queryParams,
            'route_params' => [],
            'controller' => null,
            'middlewares' => [
                'before_middlewares' => [],
                'after_middlewares' => []
            ],
            'group' => null
        ];
    }

    /**
     * Recupera a url da rota.
     *
     * @param string $route
     * @return string
     */
    private function getRouteUrl(string $route) : string {
        $regexp = self::$regexp['url_regexp_delimiter'];
        if(preg_match($regexp, $route)) {
            //Se a rota possui uma query string.
            $routeUrl = preg_replace($regexp, '', $route);
        }
        return isset($routeUrl) ? $routeUrl : $route;
    }

    /**
     * Converte a url passada em uma expressão regular valida.
     *
     * @param string $url
     * @return string
     */
    private function convertUrlToRegexp(string $url) : string {
        $urlParts = explode('/', $url);
        $urlRegexp = "/^";
        foreach ($urlParts as $key => $part) {
            //Percorre cada parte da url.

            if(preg_match('/^\{.+\}$/', $part)) {
                //Se for uma parte oculta da url.

                preg_match('/^\{.+(=|:)(.+)\}$/', $part, $match); 
                if(isset($match[1])) {
                    //Se a parte oculta possui uma expressão regular customizada.
                    $urlRegexp .= $match[2] . '\/';
                    continue;
                }

                //Se a parte oculta não possui uma expressão regular propria.
                $urlRegexp .= '[a-zA-Z0-9-_]+\/';
                continue;
            }   

            //Se é uma parte literal da url.
            $urlRegexp .= $part . '\/';
        }

        //Corrigi a expressão antes de retornar.
        $urlRegexp = $urlRegexp == '/^\/\/' ? '/^\/$/' : $urlRegexp . '?$/';
        return $urlRegexp;
    }

    /**
     * Recupera os parametros ocultos da url da rota.
     *
     * @param string $url
     * @return array
     */
    private function getUrlHiddenParams(string $url) : array {
        $urlParts = explode('/', $url);
        array_shift($urlParts);
        $urlParams = array();
        foreach ($urlParts as $key => $part) {
            //Percorre cada parte da url.

            preg_match('/^\{([a-zA-Z0-9-_]+)((:|=).+)?\}$/', $part, $match);
            if(isset($match[1])) {
                //Se essa for uma parte oculta da url.
                $urlParams[$match[1]] = $key;
            }
        }
        return $urlParams;
    }

    /**
     * Recupera todos os parametros GET da query string da rota.
     *
     * @param string $route
     * @return array
     */
    private function getRouteQueryParams(string $route) : array {
        $regexp = self::$regexp['route_query_regexp'];
        $queryParams = [];
        preg_match($regexp, $route, $match);
        if(isset($match[2]) && !empty($match[2])) {
            //Se a rota possuir uma query string.
            preg_match_all("/([a-zA-Z0-9-_]+)/", $match[2], $matches);
            $queryParams = $matches[1];
        }
        return $queryParams;
    }
}

?>