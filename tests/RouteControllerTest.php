<?php

use PHPUnit\Framework\TestCase;
use Router\Controller\RouteController;

final class RouteControllerTest extends TestCase {

    private RouteController $routeController;

    /**
     * Inicia as configurações.
     *
     * @return void
     */
    public function setUp() : void {
        $this->routeController = new RouteController;
    }

    /**
     * Testa o metodo de validação de controladores.
     *
     * @return void
     */
    public function testIsValidController() : void {
        $reflectionMethod = new ReflectionMethod($this->routeController, 'isValidController');
        $reflectionMethod->setAccessible(true);

        //Testes com controladores validos.
        $this->assertTrue($reflectionMethod->invokeArgs($this->routeController, [function() {}]));

        $this->assertTrue($reflectionMethod->invokeArgs($this->routeController, ['Testes\ClasseDeTestes']));

        $this->assertTrue($reflectionMethod->invokeArgs($this->routeController, ['Testes\ClasseDeTestes@publicController']));

        $this->assertTrue($reflectionMethod->invokeArgs($this->routeController, ['Testes\ClasseDeTestes@staticController']));

        //Teste com controladores invalidos.
        $this->assertFalse($reflectionMethod->invokeArgs($this->routeController, ['function() {}']));

        $this->assertFalse($reflectionMethod->invokeArgs($this->routeController, ['Testes\ClasseDeTestesInvalida']));

        $this->assertFalse($reflectionMethod->invokeArgs($this->routeController, ['Testes\ClasseDeTestes@invalidController']));
    }

}

?>