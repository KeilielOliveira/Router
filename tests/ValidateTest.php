<?php

use PHPUnit\Framework\TestCase;

class ValidateTest extends TestCase {

    /**
     * Testa o metodo isValidUri() com diversas URLS.
     *
     * @return void
     */
    public function testIsValidUri() {
        $validate = new Router\Validate;

        $result = $validate->isValidUri('/');
        $this->assertTrue($result);

        $result = $validate->isValidUri('/home');
        $this->assertTrue($result);

        $result = $validate->isValidUri('/home/blog');
        $this->assertTrue($result);

        $result = $validate->isValidUri('/{page}');
        $this->assertTrue($result);

        $result = $validate->isValidUri('/{page=alpha}');
        $this->assertTrue($result);

        $result = $validate->isValidUri('/{page}/{page_2=(shop|blog)}');
        $this->assertTrue($result);

        $result = $validate->isValidUri('/{page}/{page_2=(shop|blog)}/{page_3=alpha}');
        $this->assertTrue($result);

        $result = $validate->isValidUri('/page:param');
        $this->assertTrue($result);

        $result = $validate->isValidUri('/:param');
        $this->assertTrue($result);

        $result = $validate->isValidUri('/{page}:param-1&param-2');
        $this->assertTrue($result);

        $result = $validate->isValidUri('/{page}:param-1&param-2&param-3');
        $this->assertTrue($result);
    }

    public function testIsValidClassOrFunction() {

        $validate = new Router\Validate();

        $result = $validate->isValidClassOrFunction(function() {});
        $this->assertTrue($result);

        $function = function() {};
        $result = $validate->isValidClassOrFunction($function);
        $this->assertTrue($result);

        $result = $validate->isValidClassOrFunction('Router\Methods');
        $this->assertTrue($result);

        $result = $validate->isValidClassOrFunction('Router\Validate@isValidUri');
        $this->assertTrue($result);

        $result = $validate->isValidClassOrFunction('Router\Methods@__construct');
        $this->assertTrue($result);

        $function = '';
        $result = $validate->isValidClassOrFunction($function);
        $this->assertFalse($result);

        $result = $validate->isValidClassOrFunction('Teste');
        $this->assertFalse($result);

        $result = $validate->isValidClassOrFunction('Router\Validate');
        $this->assertFalse($result);

    }

}

?>