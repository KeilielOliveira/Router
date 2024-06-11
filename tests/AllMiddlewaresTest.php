<?php

use PHPUnit\Framework\TestCase;
use Router\Middlewares\GlobalMiddlewares;
use Router\Middlewares\GroupMiddlewares;
use Router\Middlewares\RouteMiddlewares;

final class AllMiddlewaresTest extends TestCase {

    private GlobalMiddlewares $globalMiddlewares;
    private GroupMiddlewares $groupMiddlewares;
    private RouteMiddlewares $routeMiddlewares;

    /**
     * Inicia as configurações.
     *
     * @return void
     */
    public function setUp() : void {
        $this->globalMiddlewares = new GlobalMiddlewares;
        $this->groupMiddlewares = new GroupMiddlewares('/teste', 'before');
        $this->routeMiddlewares = new RouteMiddlewares;
    }

    /**
     * Testa o metodo de validação de middlewares globais.
     *
     * @return void
     */
    public function testIsValidGlobalMiddlewares() : void {
        $reflectionMethod = new ReflectionMethod($this->globalMiddlewares, 'isValidMiddlewares');
        $reflectionMethod->setAccessible(true);

        //Teste do metodo de validação de middlewares globais com middlewares validos.
        $this->assertTrue($reflectionMethod->invokeArgs($this->globalMiddlewares, [[
            function() {}, 'Testes\ClasseDeTestes',
            'Testes\ClasseDeTestes@publicMiddleware', 'Testes\ClasseDeTestes@staticMiddleware'
        ]]));

        //Teste do metodo de validação de middlewares globais com middlewares invalidos.
        $this->assertFalse($reflectionMethod->invokeArgs($this->globalMiddlewares, [[
            'function() {}'
        ]]));

        $this->assertFalse($reflectionMethod->invokeArgs($this->globalMiddlewares, [[
            'Testes\ClasseDeTestesInvalida'
        ]]));

        $this->assertFalse($reflectionMethod->invokeArgs($this->globalMiddlewares, [[
            'Testes\ClasseDeTestes@middlewareInvalido'
        ]]));
    }

}

?>