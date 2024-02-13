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

    public function testValidateRoute() {
        Router\Router::get('/home', function($route) {
            return $route->return();
        });

        $validate = new Router\Validate;
        $debug = Router\Debug::setDebugMode();
        $debug->setRoute('/home', 'get');
        $result = $validate->validateRoute();
        $this->assertEquals([
            'uri' => '/home',
            'query' => null,
            'url' => '/home',
            'regexp' => '\/home',
            'route_params' => []
        ], $result);
    }

    public function testRouteExists() {
        $validate = new Router\Validate;

        Router\Router::get('/me', function($route) {
            return $route->return();
        });

        $result = $validate->routeExists('/', 'POST');
        $this->assertFalse($result);

        $result = $validate->routeExists('/home', 'GET');
        $this->assertFalse($result);

        $result = $validate->routeExists('/me', 'GET');
        $this->assertTrue($result);
    }

    public function testRouteExistsInGroup() {
        $validate = new Router\Validate;

        Router\Router::group(function($group) {
            $group->get('/me', function($route) {
                return $route->return();
            });
        });

        $result = $validate->routeExistsInGroup('/', 'POST');
        $this->assertFalse($result);

        $result = $validate->routeExistsInGroup('/', 'GET');
        $this->assertFalse($result);

        $result = $validate->routeExistsInGroup('/me', 'GET');
        $this->assertTrue($result);
    }

}

?>