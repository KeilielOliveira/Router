<?php

use PHPUnit\Framework\TestCase;

final class RouterValidatorTest extends TestCase {

    public function testIsValidRoute() {
        $validator = new Router\RouterValidator(false);

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