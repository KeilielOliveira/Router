<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use Router\Router;
use Router\Routes\RouteValidate;

final class RouteValidateTest extends TestCase {

    /**
     * @var RouteValidate
     */
    private RouteValidate $routeValidate;

    /**
     * Inicializa variaveis uteis.
     *
     * @return void
     */
    public function setUp() : void {
        $this->routeValidate = new RouteValidate;
    }

    /**
     * Testa o metodo de validação de rotas com rotas validas.
     *
     * @return void
     */
    public function testRoutesAreValidWithValidRoutes() : void {
        $this->assertTrue($this->routeValidate->isValidRoute('/'));
        $this->assertTrue($this->routeValidate->isValidRoute('/teste'));
        $this->assertTrue($this->routeValidate->isValidRoute('/teste/teste-1'));
        $this->assertTrue($this->routeValidate->isValidRoute('/{teste}'));
        $this->assertTrue($this->routeValidate->isValidRoute('/{teste-page}/{teste_index_1}'));
        $this->assertTrue($this->routeValidate->isValidRoute('/{teste:(test-page|test-id)}'));
        $this->assertTrue($this->routeValidate->isValidRoute('/{teste=(test-page|test-id)}'));
        $this->assertTrue($this->routeValidate->isValidRoute('/{teste-page:test-[a-z]+}/{teste-id=[0-9]{8}}'));
        $this->assertTrue($this->routeValidate->isValidRoute('/:test-id'));
        $this->assertTrue($this->routeValidate->isValidRoute('/:test-id&test-name'));
    } 

    /**
     * Testa o metodo de validação de rotas com rotas invalidas.
     *
     * @return void
     */
    public function testRoutesAreValidWithInvalidRoutes() : void {
        $this->assertFalse($this->routeValidate->isValidRoute(''));
        $this->assertFalse($this->routeValidate->isValidRoute('//'));
        $this->assertFalse($this->routeValidate->isValidRoute('/ teste'));
        $this->assertFalse($this->routeValidate->isValidRoute('/teste*'));
        $this->assertFalse($this->routeValidate->isValidRoute('/teste/'));
        $this->assertFalse($this->routeValidate->isValidRoute('/teste/ teste-id'));
        $this->assertFalse($this->routeValidate->isValidRoute('/teste/teste*id'));
        $this->assertFalse($this->routeValidate->isValidRoute('{teste}'));
        $this->assertFalse($this->routeValidate->isValidRoute('/{}'));
        $this->assertFalse($this->routeValidate->isValidRoute('/{**}'));
        $this->assertFalse($this->routeValidate->isValidRoute('/{teste:}'));
        $this->assertFalse($this->routeValidate->isValidRoute('/{teste=}'));
        $this->assertFalse($this->routeValidate->isValidRoute('/{teste/{teste-id}'));
        $this->assertFalse($this->routeValidate->isValidRoute(':id'));
        $this->assertFalse($this->routeValidate->isValidRoute('/:'));
        $this->assertFalse($this->routeValidate->isValidRoute('/::'));
        $this->assertFalse($this->routeValidate->isValidRoute('/:**'));
        $this->assertFalse($this->routeValidate->isValidRoute('/:id&'));
    }

    /**
     * Registra rotas para testes.
     *
     * @return void
     */
    private function registerRoutesForTests() : void {
        $router = new Router;
        $router->get('/existing-route', function() {});
        $router->post('/existing-route', function() {});
        $router->put('/existing-route', function() {});
        $router->delete('/existing-route', function() {});
        $router->update('/existing-route', function() {});
        $router->patch('/existing-route', function() {});
    }

    /**
     * Testa o metodo de verificação de se uma rota existe, com rotas registradas.
     *
     * @return void
     */
    public function testRouteExistsWithRegisteredRoutes() : void {
        $this->registerRoutesForTests();
        $this->assertTrue($this->routeValidate->routeExists('GET', '/existing-route'));
        $this->assertTrue($this->routeValidate->routeExists('POST', '/existing-route'));
        $this->assertTrue($this->routeValidate->routeExists('PUT', '/existing-route'));
        $this->assertTrue($this->routeValidate->routeExists('DELETE', '/existing-route'));
        $this->assertTrue($this->routeValidate->routeExists('UPDATE', '/existing-route'));
        $this->assertTrue($this->routeValidate->routeExists('PATCH', '/existing-route'));
    }

    /**
     * Testa o metodo de verificação de se uma rota existe, com rotas não registradas.
     *
     * @return void
     */
    public function testRouteExistsWithUnregisteredRoutes() : void {
        $this->assertFalse($this->routeValidate->routeExists('GET', '/non-existent-route'));
        $this->assertFalse($this->routeValidate->routeExists('POST', '/non-existent-route'));
        $this->assertFalse($this->routeValidate->routeExists('PUT', '/non-existent-route'));
        $this->assertFalse($this->routeValidate->routeExists('DELETE', '/non-existent-route'));
        $this->assertFalse($this->routeValidate->routeExists('UPDATE', '/non-existent-route'));
        $this->assertFalse($this->routeValidate->routeExists('PATCH', '/non-existent-route'));
    }
}

?>