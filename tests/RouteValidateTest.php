<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use Router\Router;
use Router\RouterException;
use Router\Routes\RouteValidate;

final class RouteValidateTest extends TestCase {

    /**
     * Testa se o metodo de validação de rotas está validando corretamente as rotas validas.
     *
     * @return void
     */
    public function testRoutesAreValid() {
        $validator = new RouteValidate;
        $this->assertTrue($validator->isValidRoute('/'));
        $this->assertTrue($validator->isValidRoute('/home'));
        $this->assertTrue($validator->isValidRoute('/{page}/{id:[0-9]{8}}'));
        $this->assertTrue($validator->isValidRoute('/:id'));
        $this->assertTrue($validator->isValidRoute('/{page}:id&token'));
    }

    /**
     * Testa se o metodo de validação de rotas está validando corretamente as rotas invalidas.
     *
     * @return void
     */
    #[DataProvider('invalidRouteProvider')]
    public function testRoutesAreInvalid(string $invalidRoute) {
        $validator = new RouteValidate;

        $this->expectException(RouterException::class);
        $this->expectExceptionCode(103);
        $validator->isValidRoute($invalidRoute);
    }

    /**
     * Provedor de rotas invalidas para o metodo testRoutesAreInvalid().
     *
     * @return array
     */
    public static function invalidRouteProvider() : array {
        return [
            ['//'], ['/home/'], ['/{}'], ['/{page}/{}'], ['/ {}'], ['/{page:}'], ['{page=}'], ['/{:}'], ['{}'],
            ['/:'], ['/:id&'], ['/::']
        ];
    }

    /**
     * Testa o metodo de verificação da existencia das rotas com rotas existentes.
     *
     * @return void
     */
    public function testRouteExistsWithExistingRoutes() {
        $router = new Router;
        $validator = new RouteValidate;

        $router->get('/', function() {});
        $router->get('/home', function() {});
        $router->get('/{page}:id', function() {});

        $this->assertTrue($validator->routeExists('GET', '/'));
        $this->assertTrue($validator->routeExists('GET', '/home'));
        $this->assertTrue($validator->routeExists('GET', '/{page}:id'));
    }

    /**
     * Testa o metodo de verificação da existencia das rotas com rotas inexistentes.
     *
     * @param string $requestMethod
     * @param string $route
     * @return void
     */
    #[DataProvider('nonExistentgRoutesProvider')]
    public function testRouteExistsWithNonExistentoutes(string $requestMethod, string $route) {
        $validator = new RouteValidate;

        $this->expectException(RouterException::class);
        $this->expectExceptionCode(104);
        $validator->routeExists($requestMethod, $route);
    }

    /**
     * Provedor de rotas inexistentes para o metodo testRouteExistsWithNonExistentoutes().
     *
     * @return array
     */
    public static function nonExistentgRoutesProvider() : array {
        return [
            ['UPDATE', '/'],
            ['POST', '/{page}'],
            ['PUT', '/:id&token']
        ];
    }
}

?>