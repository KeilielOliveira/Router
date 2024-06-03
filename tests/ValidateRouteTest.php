<?php

use PHPUnit\Framework\TestCase;

final class ValidateRouteTest extends TestCase {

    public function testIsValidRoute() {
        $config = new Router\RouterConfig;
        $config->defineValidateRouteConfig([
            'return_exception' => false
        ]);
        $validator = new Router\ValidateRoute();

        //Testes de rotas validas. 
        $result = $validator->isValidRoute('/');
        $this->assertTrue($result);

        $result = $validator->isValidRoute('/home');
        $this->assertTrue($result);

        $result = $validator->isValidRoute('/{page}/id');
        $this->assertTrue($result);

        $result = $validator->isValidRoute('/{page}/{id=[0-9]+}:id');
        $this->assertTrue($result);

        $result = $validator->isValidRoute('/home:id&token');
        $this->assertTrue($result);

        //Testes de rotas invalidas.
        $result = $validator->isValidRoute('//');
        $this->assertFalse($result);

        $result = $validator->isValidRoute('/home=');
        $this->assertFalse($result);

        $result = $validator->isValidRoute('/{}');
        $this->assertFalse($result);

        $result = $validator->isValidRoute('/{page=}');
        $this->assertFalse($result);

        $result = $validator->isValidRoute('/:id&=');
        $this->assertFalse($result);
    }

}

?>