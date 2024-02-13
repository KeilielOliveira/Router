<?php 

use PHPUnit\Framework\TestCase;

class UtilsTest extends TestCase {

    public function testPrepareClassOrFunction() {
        $utils = new Router\Utils();

        $result = $utils->prepareClassOrFunction(function() {});
        $this->assertEquals([
            'type' => 'function',
            'function' => function() {}
        ], $result);

        $result = $utils->prepareClassOrFunction('Router\Router');
        $this->assertEquals([
            'type' => 'class',
            'class' => 'Router\Router'
        ], $result);

        $result = $utils->prepareClassOrFunction('Router\Router@get');
        $this->assertEquals([
            'type' => 'class',
            'class' => 'Router\Router',
            'method' => 'get'
        ], $result);
    }

    public function testPrepareRoute() {
        $utils = new Router\Utils();

        $result = $utils->prepareRoute('/');
        $this->assertEquals([
            'uri' => '/',
            'regexp' => '\/',
            'query' => null,
            'route_params' => [],
            'url' => '/'
        ], $result);

        $result = $utils->prepareRoute('/home');
        $this->assertEquals([
            'uri' => '/home',
            'regexp' => '\/home',
            'query' => null,
            'route_params' => [],
            'url' => '/home'
        ], $result);

        $result = $utils->prepareRoute('/home/shop');
        $this->assertEquals([
            'uri' => '/home/shop',
            'regexp' => '\/home\/shop',
            'query' => null,
            'route_params' => [],
            'url' => '/home/shop'
        ], $result);

        $result = $utils->prepareRoute('/:param');
        $this->assertEquals([
            'uri' => '/:param',
            'regexp' => '\/',
            'query' => 'param',
            'route_params' => [],
            'url' => '/'
        ], $result);

        $result = $utils->prepareRoute('/:param_1&param_2&param_3');
        $this->assertEquals([
            'uri' => '/:param_1&param_2&param_3',
            'regexp' => '\/',
            'query' => 'param_1&param_2&param_3',
            'route_params' => [],
            'url' => '/'
        ], $result);


        $result = $utils->prepareRoute('/{page}');
        $this->assertEquals([
            'uri' => '/{page}',
            'regexp' => '\/[a-zA-Z0-9-_]+',
            'query' => null,
            'route_params' => [
                'page' => null
            ],
            'url' => '/{page}'
        ], $result);

        $result = $utils->prepareRoute('/{page=alpha-numeric-upper}');
        $this->assertEquals([
            'uri' => '/{page=alpha-numeric-upper}',
            'regexp' => '\/[A-Z0-9-_]+',
            'query' => null,
            'route_params' => [
                'page' => null
            ],
            'url' => '/{page=alpha-numeric-upper}'
        ], $result);

        $result = $utils->prepareRoute('/{page=(home|blog)}');
        $this->assertEquals([
            'uri' => '/{page=(home|blog)}',
            'regexp' => '\/(home|blog)',
            'query' => null,
            'route_params' => [
                'page' => null
            ],
            'url' => '/{page=(home|blog)}'
        ], $result);

        $result = $utils->prepareRoute('/{page}/{page2}');
        $this->assertEquals([
            'uri' => '/{page}/{page2}',
            'regexp' => '\/[a-zA-Z0-9-_]+\/[a-zA-Z0-9-_]+',
            'query' => null,
            'route_params' => [
                'page' => null,
                'page2' => null
            ],
            'url' => '/{page}/{page2}'
        ], $result);

    }

    public function testConverteToRegExp() {
        $utils = new Router\Utils;

        $result = $utils->converteToRegExp('/');
        $this->assertEquals('\/', $result[0]);

        $result = $utils->converteToRegExp('/home');
        $this->assertEquals('\/home', $result[0]);

        $result = $utils->converteToRegExp('/home/me');
        $this->assertEquals('\/home\/me', $result[0]);

        $result = $utils->converteToRegExp('/{page}');
        $this->assertEquals('\/[a-zA-Z0-9-_]+', $result[0]);

        $result = $utils->converteToRegExp('/{page=numeric}');
        $this->assertEquals('\/[0-9-_]+', $result[0]);

        $result = $utils->converteToRegExp('/{page=(blog|shop)}');
        $this->assertEquals('\/(blog|shop)', $result[0]);
    }

    public function testGetRegExp() {
        $utils = new Router\Utils();

        $result = $utils->getRegExp('alpha');
        $this->assertEquals('[a-zA-Z-_]+', $result);

        $result = $utils->getRegExp('alpha-upper');
        $this->assertEquals('[A-Z-_]+', $result);

        $result = $utils->getRegExp('alpha-lower');
        $this->assertEquals('[a-z-_]+', $result);

        $result = $utils->getRegExp('numeric');
        $this->assertEquals('[0-9-_]+', $result);

        $result = $utils->getRegExp('alpha-numeric');
        $this->assertEquals('[a-zA-Z0-9-_]+', $result);

        $result = $utils->getRegExp('alpha-numeric-upper');
        $this->assertEquals('[A-Z0-9-_]+', $result);

        $result = $utils->getRegExp('alpha-numeric-lower');
        $this->assertEquals('[a-z0-9-_]+', $result);

        $result = $utils->getRegExp('teste');
        $this->assertEquals('teste', $result);
    }

    public function testExecuteClassOrFunction() {
        $utils = new Router\Utils();

        $function = [
            'type' => 'function',
            'function' => function($msg) {
                return $msg;
            }
        ];

        $result = $utils->executeClassOrFunction($function, ['Tudo certo!']);
        $this->assertEquals('Tudo certo!', $result);
        
        $function = [
            'type' => 'class',
            'class' => 'Tests\Tests',
        ];
        $result = $utils->executeClassOrFunction($function, ['OK']);
        $this->assertEquals('OK', $result);

        $function['method'] = 'return';
        $result = $utils->executeClassOrFunction($function, ['Hello']);
        $this->assertEquals('Hello', $result);

        $result = $utils->executeClassOrFunction($function, [false]);
        $this->assertFalse($result);
    }

    public function testGetRouteParams() {
        $utils = new Router\Utils();
        $debug = Router\Debug::setDebugMode();
        $debug->setRoute('/home', 'get');
        $result = $utils->getRouteParams(['route_params' => ['page' => 1]]);
        $this->assertEquals(['page' => 'home'], $result['route_params']);
    }

}

?>