<?php

use PHPUnit\Framework\TestCase;

final class PrepareRouteTest extends TestCase {

    public function testPrepareRoute() {
        $prepare = new Router\Routes\PrepareRoute;

        $result = $prepare->prepareRoute('/:id');
        $expected = [
            'route_url' => '/',
            'url_regexp' => '/^\/$/',
            'url_hidden_params' => [],
            'get_params' => ['id'] 
        ];
        $this->assertEquals($expected, $result);
    }

}

?>