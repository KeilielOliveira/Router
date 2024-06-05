<?php

use PHPUnit\Framework\TestCase;

final class RouteMiddlewaresTest extends TestCase {

    /**
     * Copia do metodo de validação de middlewares de rotas para testes.
     * 
     * @return boolean
     */
    public function isValidMiddlewares(string | array | callable $middlewares) : bool {
        $middlewares = !is_array($middlewares) ? [$middlewares] : $middlewares;
        foreach ($middlewares as $key => $middleware) {
            //Percorre cada middleware.

            if(is_callable($middleware)) {
                //Se o middleware for uma função valida.
                continue;
            }else {
                //Se o middleware for uma classe.

                if(str_contains($middleware, '@')) {
                    //Se foi passado um metodo especifico da classe.
                    [$class, $method] = explode('@', $middleware);
                }else {
                    //Se não foi passado um metodo especifico da classe.
                    [$class, $method] = [$middleware, 'middleware'];
                }

                if(class_exists($class) && method_exists($class, $method)) {
                    //Se a classe e o metodo exisitirem.

                    $reflectionMethod = new ReflectionMethod($class, $method);
                    if($reflectionMethod->isPublic() || $reflectionMethod->isStatic()) {
                        //Se o metodo for publico ou estatico.
                        continue;
                    }
                }
            }
            return false;
        }
        return true;
    }

    /**
     * Testesd do metodo de validação de middlewares de rotas.
     *
     * @return void
     */
    public function testIsValidMiddlewares() {
        //Testes de middlewares de rotas validos.
        $this->assertTrue($this->isValidMiddlewares(function() {}));
        $this->assertTrue($this->isValidMiddlewares('Tests\ClasseDeTestes'));
        $this->assertTrue($this->isValidMiddlewares('Tests\ClasseDeTestes@middlewareMethod'));
        $this->assertTrue($this->isValidMiddlewares([
            function() {}, 'Tests\ClasseDeTestes', 'Tests\ClasseDeTestes@middlewareMethod'
        ]));

        //Testes de middlewares de rotas invalidos.
        $this->assertFalse($this->isValidMiddlewares('function() {}'));
        $this->assertFalse($this->isValidMiddlewares('Tests\ClasseDeTestesInvalida'));
        $this->assertFalse($this->isValidMiddlewares('Tests\ClasseDeTestes@invalidMiddlewareMethod'));
        $this->assertFalse($this->isValidMiddlewares([
            'function() {}', 'Tests\ClasseDeTestesInvalida', 'Tests\ClasseDeTestes@invalidMiddlewareMethod'
        ]));
    }

}

?>