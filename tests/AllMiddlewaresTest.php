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

    /**
     * Testa o metodo de validação de middlewares de grupo.
     *
     * @return void
     */
    public function testIsValidGroupMiddlewares() : void {
        $reflectionMethod = new ReflectionMethod($this->groupMiddlewares, 'isValidMiddlewares');
        $reflectionMethod->setAccessible(true);

        //Testes do metodo de validação de middlewares de grupo com middlewares validos.
        $this->assertTrue($reflectionMethod->invokeArgs($this->groupMiddlewares, [function() {}]));

        $this->assertTrue($reflectionMethod->invokeArgs($this->groupMiddlewares, ['Testes\ClasseDeTestes']));

        $this->assertTrue($reflectionMethod->invokeArgs($this->groupMiddlewares, ['Testes\ClasseDeTestes@publicMiddleware']));

        $this->assertTrue($reflectionMethod->invokeArgs($this->groupMiddlewares, ['Testes\ClasseDeTestes@staticMiddleware']));

        $this->assertTrue($reflectionMethod->invokeArgs($this->groupMiddlewares, [[
            function() {}, 'Testes\ClasseDeTestes@publicMiddleware', 'Testes\ClasseDeTestes@staticMiddleware'
        ]]));

        //Testes do metodo de validação de middlewares de grupo com middlewares invalidos.
        $this->assertFalse($reflectionMethod->invokeArgs($this->groupMiddlewares, ['function() {}']));

        $this->assertFalse($reflectionMethod->invokeArgs($this->groupMiddlewares, ['Testes\ClasseDeTestesInvalida']));

        $this->assertFalse($reflectionMethod->invokeArgs($this->groupMiddlewares, ['Testes\ClasseDeTestes@middlewareInvalido']));

        $this->assertFalse($reflectionMethod->invokeArgs($this->groupMiddlewares, [[
            function() {}, 'Testes\ClasseDeTestes', 'Testes\ClasseDeTestes@middlewareInvalido'
        ]]));
    }

    /**
     * Testa o metodo de validação de middlewares individuais de rotas.
     *
     * @return void
     */
    public function testIsValidRouteMiddlewares() : void {
        $reflectionMethod = new ReflectionMethod($this->routeMiddlewares, 'isValidMiddlewares');
        $reflectionMethod->setAccessible(true);

        //Testes do metodo de validação de middlewares de rota com middlewares validos.
        $this->assertTrue($reflectionMethod->invokeArgs($this->routeMiddlewares, [function() {}]));

        $this->assertTrue($reflectionMethod->invokeArgs($this->routeMiddlewares, ['Testes\ClasseDeTestes']));

        $this->assertTrue($reflectionMethod->invokeArgs($this->routeMiddlewares, ['Testes\ClasseDeTestes@publicMiddleware']));

        $this->assertTrue($reflectionMethod->invokeArgs($this->routeMiddlewares, ['Testes\ClasseDeTestes@staticMiddleware']));

        $this->assertTrue($reflectionMethod->invokeArgs($this->routeMiddlewares, [[
            function() {}, 'Testes\ClasseDeTestes@publicMiddleware', 'Testes\ClasseDeTestes@staticMiddleware'
        ]]));

        //Testes do metodo de validação de middlewares de rota com middlewares invalidos.
        $this->assertFalse($reflectionMethod->invokeArgs($this->routeMiddlewares, ['function() {}']));

        $this->assertFalse($reflectionMethod->invokeArgs($this->routeMiddlewares, ['Testes\ClasseDeTestesInvalida']));

        $this->assertFalse($reflectionMethod->invokeArgs($this->routeMiddlewares, ['Testes\ClasseDeTestes@middlewareInvalido']));

        $this->assertFalse($reflectionMethod->invokeArgs($this->routeMiddlewares, [[
            function() {}, 'Testes\ClasseDeTestes', 'Testes\ClasseDeTestes@middlewareInvalido'
        ]]));
    }

}

?>