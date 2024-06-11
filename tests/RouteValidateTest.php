<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
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
}

?>