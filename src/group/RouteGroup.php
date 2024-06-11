<?php 

namespace Router\Group;

use Router\Interfaces\HttpMethodsInterface;
use Router\RouterConfig;
use Router\Routes\PrepareRoute;
use Router\Routes\RouteValidate;
use Router\RouterException;

class RouteGroup extends RouterConfig implements HttpMethodsInterface {

    private RouteValidate $routeValidate;
    private PrepareRoute $prepareRoute;

    /**
     * Base da rota usada em todas as rotas de grupo.
     *
     * @var string
     */
    private string $group;

    /**
     * Inicia a classe.
     *
     * @param string $base
     */
    public function __construct(string $base) {
        $this->group = $base;
        self::$groups[$base] = [
            'group_routes' => [],
            'middlewares' => [
                'before_middlewares' => [],
                'after_middlewares' => []
            ]
        ];

        //Instancias de classes.
        $this->routeValidate = new RouteValidate;
        $this->prepareRoute = new PrepareRoute;

    }

    /**
     * Registra a rota passada.
     *
     * @param string $requestMethod
     * @param string $route
     * @return boolean
     */
    private function registerGroupRoute(string $requestMethod, string $route) : bool {
        $route = $this->group . $route;
        if($this->routeValidate->isValidRoute($route)) {
            //Se a rota for valida.
            $routeConfig = $this->prepareRoute->prepareRoute($route);
            $routeConfig['group'] = $this->group;
            $requestMethod = strtoupper($requestMethod);

            if(!$this->routeValidate->routeExists($requestMethod, $route)) {
                //Se essa rota já não tiver sido registrada.

                self::$registeredRoutes[$requestMethod][$route] = $routeConfig;
                self::$lastRegisteredRoute = [
                    'request_method' => $requestMethod,
                    'route' => $route
                ];

                //Registra essa rota no grupo.
                self::$groups[$this->group]['group_routes'][] = $route;
                return true;
            }

            $group = $this->group;
            $message = "A rota <b>$route</b> do tipo <b>$requestMethod</b> do grupo <b>$group</b> já foi registrada.";
            throw new RouterException($message, 102);
        }

        $group = $this->group;
        $message = "A rota <b>$route</b> do tipo <b>$requestMethod</b> do grupo <b>$group</b> é invalida.";
        throw new RouterException($message, 103);
    }

    /**
     * Registra uma rota de grupo do tipo GET.
     *
     * @param string $route
     * @param callable $callback
     * @return void
     */
    public function get(string $route, callable $callback): void {
        $this->registerGroupRoute('GET', $route);
        $callback(new \Router\Routes\RouteMethods);
    }

    /**
     * Registra uma rota de grupo do tipo POST.
     *
     * @param string $route
     * @param callable $callback
     * @return void
     */
    public function post(string $route, callable $callback): void {
        $this->registerGroupRoute('POST', $route);
        $callback(new \Router\Routes\RouteMethods);
    }

    /**
     * Registra uma rota de grupo do tipo PUT.
     *
     * @param string $route
     * @param callable $callback
     * @return void
     */
    public function put(string $route, callable $callback): void {
        $this->registerGroupRoute('PUT', $route);
        $callback(new \Router\Routes\RouteMethods);
    }

    /**
     * Registra uma rota de grupo do tipo DELETE.
     *
     * @param string $route
     * @param callable $callback
     * @return void
     */
    public function delete(string $route, callable $callback): void {
        $this->registerGroupRoute('DELETE', $route);
        $callback(new \Router\Routes\RouteMethods);
    }

    /**
     * Registra uma rota de grupo do tipo UPDATE.
     *
     * @param string $route
     * @param callable $callback
     * @return void
     */
    public function update(string $route, callable $callback): void {
        $this->registerGroupRoute('UPDATE', $route);
        $callback(new \Router\Routes\RouteMethods);
    }

    /**
     * Registra uma rota de grupo do tipo PATCH.
     *
     * @param string $route
     * @param callable $callback
     * @return void
     */
    public function patch(string $route, callable $callback): void {
        $this->registerGroupRoute('PATCH', $route);
        $callback(new \Router\Routes\RouteMethods);
    }

    /**
     * Registra middlewares de grupo que serão executados antes das rotas.
     */
    public function beforeGroupMiddlewares(string | array | callable $beforeMiddlewares) : void {
        $groupMiddlewares = new \Router\Middlewares\GroupMiddlewares($this->group, 'before');
        $groupMiddlewares->registerGroupMiddlewares($beforeMiddlewares);
    }

    /**
     * Registra middlewares de grupo que serão executados após as rotas.
     */
    public function afterGroupMiddlewares(string | array | callable $afterMiddlewares) : void {
        $groupMiddlewares = new \Router\Middlewares\GroupMiddlewares($this->group, 'after');
        $groupMiddlewares->registerGroupMiddlewares($afterMiddlewares);
    }
}

?>