<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use Router\RouterException;
use Router\Routes\PrepareRoute;

final class PrepareRouteTest extends TestCase {

    private PrepareRoute $prepareRoute;

    /**
     * Inicia as configurações dos testes.
     *
     * @return void
     */
    public function setUp() : void {

        $this->prepareRoute = new PrepareRoute;
    }

    /**
     * Testa o metodo de preparo de rotas para registro com rotas validas.
     *
     * @return void
     */
    public function testPrepareRouteWithValidRoutes() : void {

        //Url simples
        $result = $this->prepareRoute->prepareRoute('/');
        $expected = [
            'route_url' => '/',
            'url_regexp' => '/^\/$/',
            'url_hidden_params' => [],
            'get_params' => [],
            'route_params' => [],
            'controller' => null,
            'middlewares' => [
                'before_middlewares' => [],
                'after_middlewares' => []
            ],
            'group' => null
        ];
        $this->assertEquals($expected, $result);

        //Url com parametros GET
        $result = $this->prepareRoute->prepareRoute('/:id&token');
        $expected = [
            'route_url' => '/',
            'url_regexp' => '/^\/$/',
            'url_hidden_params' => [],
            'get_params' => ['id', 'token'],
            'route_params' => [],
            'controller' => null,
            'middlewares' => [
                'before_middlewares' => [],
                'after_middlewares' => []
            ],
            'group' => null
        ];
        $this->assertEquals($expected, $result);

        //Url com parametros GET e partes ocultas.
        $result = $this->prepareRoute->prepareRoute('/{page}/{id:[0-9]{8}}:token');
        $expected = [
            'route_url' => '/{page}/{id:[0-9]{8}}',
            'url_regexp' => '/^\/[a-zA-Z0-9-_]+\/[0-9]{8}\/?$/',
            'url_hidden_params' => ['page' => 0, 'id' => 1],
            'get_params' => ['token'],
            'route_params' => [],
            'controller' => null,
            'middlewares' => [
                'before_middlewares' => [],
                'after_middlewares' => []
            ],
            'group' => null
        ];
        $this->assertEquals($expected, $result);
    }

    /**
     * Provedor de rotas para o metodo testPrepareRouteWithInvalidRoutes.
     *
     * @return void
     */
    public static function routeProviderMethodTestPrepareRouteWidthInvalidRoutes() : array {
        return [
            ['/:id&token&id', 105, 'O parâmetro GET <b>id</b> é duplicado na rota <b>/:id&token&id</b>.'],
            ['/{page}/{page}', 105, 'O parametro de URL <b>page</b> da url <b>/{page}/{page}</b> é repetido.']
        ];
    }

    /**
     * Testa o metodo de preparação de rotas com rotas contendo propriedades invalidas.
     *
     * @param string $route
     * @return void
     */
    #[DataProvider('routeProviderMethodTestPrepareRouteWidthInvalidRoutes')]
    public function testPrepareRouteWithInvalidRoutes(string $route, int $code, string $message) :void {
        $this->expectException(RouterException::class);
        $this->expectExceptionCode($code);
        $this->expectExceptionMessage($message);
        $this->prepareRoute->prepareRoute($route);
    }

}

?>