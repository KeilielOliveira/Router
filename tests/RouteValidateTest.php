<?php

use PHPUnit\Framework\TestCase;
use Router\Router;
use Router\RouterException;
use Router\Routes\RouteValidate;

final class RouteValidateTest extends TestCase {

    /**
     * Testes do metodo isValidRoute da classe RouteValidate.
     *
     * @return void
     */
    public function testMethodIsValidRoute() {
        $validator = new RouteValidate;
        //Testes de validação de rotas validas.


        //Testes de rotas literais.
        $this->assertTrue($validator->isValidRoute('/'));
        $this->assertTrue($validator->isValidRoute('/account/me'));

        //Testes de rotas com partes ocultas.
        $this->assertTrue($validator->isValidRoute('/{page}'));
        $this->assertTrue($validator->isValidRoute('/{page:(home|blog)}/{id=[0-9]{8}}'));

        //Testes de rotas com query string.
        $this->assertTrue($validator->isValidRoute('/:id'));
        $this->assertTrue($validator->isValidRoute('/:id&token'));

        //Teste de uma rota invalida.
        $this->expectException('Router\RouterException');
        $validator->isValidRoute('//');
    }

    /**
     * Teste do metodo routeExists da classe RouteValidate.
     *
     * @return void
     */
    public function testMethodRouteExists() {
        $validator = new RouteValidate;
        $router = new Router;

        //Verifica se a rota passada não existe.
        $router->get('/', function() {});
        $this->assertTrue($validator->routeExists('GET', '/'));

        //Verifica se a rota passada existe.
        $this->expectException('Router\RouterException');
        $validator->routeExists('GET', '/{page}');
    }

}

?>