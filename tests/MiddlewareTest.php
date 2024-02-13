<?php 

use PHPUnit\Framework\TestCase;

class MiddlewareTest extends TestCase {

    public function testMiddlewares() {

        $middleware = new Router\Middlewares();
        $debug = Router\Debug::setDebugMode();
        $debug->skipClassOrFunctionValidate();

        $result = $middleware->before(function() {})->return();
        $this->assertEquals([
            'before' => [
                0 => [
                    'type' => 'function',
                    'function' => function() {}
                ]  
            ],
            'after' => []
        ], $result);

        $result = $middleware->after('Middleware@after')->return();
        $this->assertEquals([
            'before' => [
                0 => [
                    'type' => 'function',
                    'function' => function() {}
                ]
            ],
            'after' => [
                0 => [
                    'type' => 'class',
                    'class' => 'Middleware',
                    'method' => 'after'
                ]
            ]
        ], $result);

        $result = $middleware->before(['Middleware', 'Middleware@before', function() {}])->return();
        $this->assertEquals([
            'before' => [
                0 => [
                    'type' => 'function',
                    'function' => function() {}
                ],
                1 => [
                    'type' => 'class',
                    'class' => 'Middleware'
                ],
                2 => [
                    'type' => 'class',
                    'class' => 'Middleware',
                    'method' => 'before'
                ],
                3 => [
                    'type' => 'function',
                    'function' => function() {}
                ]
            ],
            'after' => [
                0 => [
                    'type' => 'class',
                    'class' => 'Middleware',
                    'method' => 'after'
                ]
            ]
        ], $result);


    }

}
 
?>