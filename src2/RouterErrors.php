<?php 

namespace Router;

use Exception;
use ReflectionMethod;

class RouterErrors extends RouterConfig {

    public function registerError(int $errorCode, string | callable $errorController) {
        try {

            if($this->isValidErrorController($errorCode, $errorController)) {
                //Se o controlador for valido.
                
                self::$errors[$errorCode] = $errorController;
                return true;
            }
            throw new Exception("O controlador do erro $errorCode é invalido!");
        }catch(Exception $e) {
            echo "Ocorreu um erro: " . $e->getMessage() . '<br><br>';
        }
    }

    public function executeError(int $errorCode) {
        try {

            if(isset(self::$errors[$errorCode])) {
                //Se o erro foi registrado.

                $errorController = self::$errors[$errorCode];
                if(is_callable($errorController)) {
                    //Se o controlador for uma função.
                    return $errorController();
                }else {
                    //Se o controlador for uma classe.

                    if(str_contains($errorController, '@')) {
                        //Se foi definido metodo proprio.
                        [$class, $method] = explode('@', $errorController);
                    }else {
                        //Se não foi definido um metodo proprio.
                        [$class, $method] = [$errorController, "error$errorCode"];
                    }

                    $reflectionMethod = new ReflectionMethod($class, $method);
                    if($reflectionMethod->isPublic()) {
                        //Se o metodo for publico.
                        $class = new $class;
                        return $class->$method();
                    }else{ 
                        //Se o metodo for estatico.
                        return $class::$method();
                    }
                }
            }

            throw new Exception("Não há nenhum erro com o codigo <b>$errorCode</b> registrado!");
        }catch(Exception $e) {
            echo "Ocorreu um erro: " . $e->getMessage() . '<br><br>';
        }
    }

    private function isValidErrorController(int $errorCode, string | callable $errorController) {

        if(is_callable($errorController)) {
            //Se for uma função valida.
            return true;
        }else {
            //Se for uma classe.

            if(str_contains($errorController, '@')) {
                //Se foi passado um metodo customizado.
                [$class, $method] = explode('@', $errorController);
            }else {
                //Se não foi passado um metodo customizado.
                [$class, $method] = [$errorController, "error$errorCode"];
            }

            if(class_exists($class) && method_exists($class, $method)) {
                //Se a classe exitir e tiver o metodo.

                $reflectionMethod = new ReflectionMethod($class, $method);
                if($reflectionMethod->isPublic() || $reflectionMethod->isStatic()) {
                    //Se o metodo for publico ou estatico.
                    return true;
                }
            }
        }
        return false;
    }

}

?>