<?php 

namespace Router;

class RouterConfig {
    
    /**
     * Armazena todas as expressões regulares.
     *
     * @var array
     */
    protected static array $regexp = [
        'url_regexp' => '/^\/([a-zA-Z0-9-_]+|\{[a-zA-Z0-9-_]+(=.+)?\}(\/([a-zA-Z0-9-_]+|\{[a-zA-Z0-9-_]+(=.+)?\}))*)?',
        'query_regexp' => '(:[a-zA-Z0-9-_]+(&[a-zA-Z0-9-_]+)*)?$/'
    ];

    /**
     * Armazena todas as rotas registradas.
     *
     * @var array
     */
    protected static array $registeredRoutes = [
        'GET' => array(),
        'POST' => array(),
        'PUT' => array(),
        'DELETE' => array(),
        'UPDATE' => array(),
        'PATCH' => array()
    ];

    /**
     * Armazena a chave da ultima rota registrada.
     *
     * @var array
     */
    protected static array $lastRoute;

    /**
     * Array de configurações da classe RouteController.
     *
     * @var array
     */
    protected static array $routeControllerConfig = [
        'skip_controller_validation' => false
    ];

    /**
     * Array de configurações da classe RouteMiddlewares.
     *
     * @var array
     */
    protected static array $routeMiddlewaresConfig = [
        'skip_middlewares_validation' => false
    ];

    /**
     * Array de configurações da classe ValidateRoute.
     *
     * @var array
     */
    protected static array $validateRouteConfig = [
        'return_exception' => true
    ];

    /**
     * Armazena o conteudo da respota da requisição.
     *
     * @var string
     */
    protected static string $responseContent;

    /**
     * Armazena todos os erros que podem ser retornados para a requisição.
     *
     * @var array
     */
    protected static array $errors = [];

    /**
     * Armazena os middlewares globais.
     *
     * @var array
     */
    protected static array $globalMiddlewares;



    /**
     * Redefini as configurações da classe RouteController.
     *
     * @param array $config
     * @return void
     */
    public function defineRouteControllerConfig(array $config) {
        self::$routeControllerConfig = array_merge(self::$routeControllerConfig, $config);
        return;
    }

    /**
     * Redefini as configurações da classe ValidateRoute.
     *
     * @param array $config
     * @return void
     */
    public function defineValidateRouteConfig(array $config) {
        self::$validateRouteConfig = array_merge(self::$validateRouteConfig, $config);
        return;
    }

    /**
     * Redefini as configurações da classe RouteMiddlewares.
     *
     * @param array $config
     * @return void
     */
    public function defineRouteMiddlewaresConfig(array $config) {
        self::$routeMiddlewaresConfig = array_merge(self::$routeMiddlewaresConfig, $config);
        return;
    }

}

?>