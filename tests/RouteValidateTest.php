<?php

use PHPUnit\Framework\TestCase;

final class RouteValidateTest extends TestCase {

    /**
     * Testa o metodo de validação de rotas sendo registradas.
     *
     * @return void
     */
    public function testIsValidRoute() {
        $validator = new Router\Routes\RouteValidate;

        //Testes de rotas literais validas.
        $routes = ['/', '/home', '/home/my-account'];
        foreach($routes as $key => $route) {
            $this->assertTrue($validator->isValidRoute($route));
        }

        //Testes de rotas ocultas validas.
        $routes = ['/{page}', '/{page}/{id:[0-9]{8}}', '/{page=(home|blog)}'];
        foreach($routes as $key => $route) {
            $this->assertTrue($validator->isValidRoute($route));
        }

        //Testes de rotas com query strings.
        $routes = ['/:id', '/{page}:id&token', '/{page}/{id}:token&user'];
        foreach($routes as $key => $route) {
            $this->assertTrue($validator->isValidRoute($route));
        }
    }

}

?>