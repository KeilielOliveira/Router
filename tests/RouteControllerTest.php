<?php

use PHPUnit\Framework\TestCase;
use Router\Controller\RouteController;

final class RouteControllerTest extends TestCase {

    /**
     * Copia do metodo de mesmo nome da classe RouteController para fins de teste.
     */
    private function isValidController(string | callable $controller) : bool {
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
     * Testa o metodo de validação de controladores de rotas.
     *
     * @return void
     */
    public function testMethodIsValidController() {
        //Testes de controladores validos.
        $this->assertTrue($this->isValidController(function() {}));
        $this->assertTrue($this->isValidController('Testes\ClasseDeTestes'));
        $this->assertTrue($this->isValidController('Testes\ClasseDeTestes@controllerMethod'));

        //Testes de controladores invalidos.
        $this->assertFalse($this->isValidController('function() {}'));
        $this->assertFalse($this->isValidController('Testes\ClasseDeTestesInvalida'));
        $this->assertFalse($this->isValidController('Testes\ClasseDeTestes@invalidControllerMeethod'));
    }

    /**
     * Testa o metodo responsavel pela execução dos controladores.
     *
     * @return void
     */
    public function testMethodExecuteController() {
        $controller = new RouteController;

        $result = $controller->executeController(function() {
            return true;
        });
        $this->assertTrue($result);

        $result = $controller->executeController('Testes\ClasseDeTestes');
        $this->assertTrue($result);

        $result = $controller->executeController('Testes\ClasseDeTestes@controllerMethod');
        $this->assertTrue($result);
    }

}

?>