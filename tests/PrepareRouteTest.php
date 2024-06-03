<?php

use PHPUnit\Framework\TestCase;

final class PrepareRouteTest extends TestCase {

    public function testPrepareRoute() {
        $prepare = new Router\PrepareRoute;

        $result = $prepare->prepareRoute('/');
        $expected = [
            'url' => '/',
            'url_regexp' => '\/',
            'url_params' => [],
            'query_params' => null
        ];
        $this->assertEquals($expected, $result);

        $result = $prepare->prepareRoute('/page:id');
        $expected = [
            'url' => '/page',
            'url_regexp' => '\/page\/?',
            'url_params' => [],
            'query_params' => ['id']
        ];
        $this->assertEquals($expected, $result);

        $result = $prepare->prepareRoute('/{page}');
        $expected = [
            'url' => '/{page}',
            'url_regexp' => '\/[a-zA-Z0-9-_]+\/?',
            'url_params' => ['page' => null],
            'query_params' => null
        ];
        $this->assertEquals($expected, $result);

        $result = $prepare->prepareRoute('/{page}/{id=[0-9]{8}}:user&token');
        $expected = [
            'url' => '/{page}/{id=[0-9]{8}}',
            'url_regexp' => '\/[a-zA-Z0-9-_]+\/[0-9]{8}\/?',
            'url_params' => ['page' => null, 'id' => null],
            'query_params' => ['user', 'token']
        ];
        $this->assertEquals($expected, $result);
    }

}

?>