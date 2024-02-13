<?php 

use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase {

    public function testGet() {

        $debug = Router\Debug::setDebugMode();
        $result = Router\Router::get('/', function($route) {
            $route->setParams([
                'teste' => 'Tesntando a asição de rotas.'
            ]);
            $route->setName('home');
        });
        $this->assertTrue($result);
        $route = $debug->getRoute('home', 'get');
        $this->assertIsArray($route);
    }

    public function testGroup() {
        $debug = Router\Debug::setDebugMode();
        $debug->skipClassOrFunctionValidate();

        $result = Router\Router::group(function($group) {
            $group->get('/', function($route) {
                $route->setParams(['id' => 12]);
                $route->setName('home');
                $route->controller(function() {});
                $route->middlewares(function($middlewares) {
                    $middlewares->before('BeforeMiddleware')
                    ->after('AfterMiddleware@method');
                    return $middlewares->return();
                });
                return $route->return();
            });

            $group->return();
        });

        $this->assertTrue($result);
        $route = $debug->getRoute('home', 'get');
        $this->assertEquals([
            'uri' => '/',
            'url' => '/',
            'query' => null,
            'regexp' => '\/',
            'route_params' => [],
            'params' => ['id' => 12],
            'controller' => [
                'type' => 'function',
                'function' => function() {},
            ],
            'middlewares' => [
                'before' => [
                    0 => [
                        'type' => 'class',
                        'class' => 'BeforeMiddleware'
                    ]
                ], 
                'after' => [
                    0 => [
                        'type' => 'class',
                        'class' => 'AfterMiddleware',
                        'method' => 'method'
                    ]
                ]
            ],
            'reference' => 'home'
        ], $route);

    }

}

?>