<?php

use PHPUnit\Framework\TestCase;

final class RouteControllerTest extends TestCase {

    /**
     * Copia publica do metodo de mesmo nome da classe RouteController, para testes.
     */
    public function isValidController(string | callable $controller) : bool {
        if(is_callable($controller)) {
            //Se o controlador for uma função valida.
            return true;
        }else {
            //Se o controlador foi passado como uma classe.
            if(str_contains($controller, '@')) {
                //Se foi passado um metodo especifico como controlador.
                [$class, $method] = explode('@', $controller);    
            }else {
                //Se não foi passado um metodo especifico como controlador.
                [$class, $method] = [$controller, 'controller'];
            }

            if(class_exists($class) && method_exists($class, $method)) {
                //Se a classe e o metodo existirem.

                $reflectionMethod = new ReflectionMethod($class, $method);
                if($reflectionMethod->isPublic() || $reflectionMethod->isStatic()) {
                    //Se o metodo for publico ou estatico.
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Lida com os testes do metodo de validação de controladores de rotas.
     *
     * @return void
     */
    public function testIsValidController() {
        //Testes de controladores validos.
        $this->assertTrue($this->isValidController(function() {}));
        $this->assertTrue($this->isValidController('Tests\ClasseDeTestes'));
        $this->assertTrue($this->isValidController('Tests\ClasseDeTestes@controllerMethod'));

        //Testes de controladores invalidos.
        $this->assertFalse($this->isValidController('function() {}'));
        $this->assertFalse($this->isValidController('Tests\ClasseDeTestesInvalida'));
        $this->assertFalse($this->isValidController('Tests\ClasseDeTests@invalidControllerMethod'));
    }

}

?>