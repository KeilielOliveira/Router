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

}

?>