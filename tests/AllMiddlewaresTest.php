<?php

use PHPUnit\Framework\TestCase;
use Router\Middlewares\RouteMiddlewares;

final class AllMiddlewaresTest extends TestCase {

    /**
     * Copia do metodo padrão de validação de middlewares das classes
     * GlobalMiddlewares, GroupMiddlewares e RouteMiddlewares.
     * 
     * @return boolean
     */
    private function isValidMiddlewares(array $middlewares) : bool {
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
     * Testa o metodo usado nas validações de middlewares.
     *
     * @return void
     */
    public function testMethodIsValidMiddlewares() {
        //Testes de middlewares validos.
        $this->assertTrue($this->isValidMiddlewares([function() {}]));
        $this->assertTrue($this->isValidMiddlewares(['Testes\ClasseDeTestes']));
        $this->assertTrue($this->isValidMiddlewares(['Testes\ClasseDeTestes@middlewareMethod']));

        //Testes de middlewares invalidos.
        $this->assertFalse($this->isValidMiddlewares(['function() {}']));
        $this->assertFalse($this->isValidMiddlewares(['Testes\ClasseDeTestesInvalida']));
        $this->assertFalse($this->isValidMiddlewares(['Testes\ClasseDeTestes@invalidMiddlewareMethod']));
    }

    /**
     * Testa o metodo responsavel pela execução dos middlewares.
     *
     * @return void
     */
    public function testMethodExecuteMiddlewares() {
        $middlewares = new RouteMiddlewares;

        $result = $middlewares->executeMiddlewares(
            [function() {},
            'Testes\ClasseDeTestes',
            'Testes\ClasseDeTestes@middlewareMethod'
        ]);
        $this->assertTrue($result);
    }
}

?>