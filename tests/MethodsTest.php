<?php

use PHPUnit\Framework\TestCase, Router\Methods;

class MethodsTest extends TestCase {

    public function testController() {
        $methods = new Methods('/');
        $result = $methods->controller(function() {});
        $this->assertInstanceOf(Methods::class, $result);

        $result = $methods->controller('Router\Methods');
        $this->assertInstanceOf(Methods::class, $result);

        $result = $methods->controller('Router\Methods@controller');
        $this->assertInstanceOf(Methods::class, $result);
    }

}

?>