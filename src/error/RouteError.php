<?php 

namespace Router\Error;

use Router\RouterConfig;
use Router\RouterException;
use ReflectionMethod;

class RouteError extends RouterConfig {

    /**
     * Codigo do erro.
     *
     * @var integer
     */
    private int $errorCode;

    /**
     * Inicia uma nova pagina de erro.
     *
     * @param integer $code
     */
    public function __construct(int $code) {
        if(in_array($code, self::$acceptedErrorCodes)) {
            //Se for um codigo de erro valido.
            self::$errors[$code] = [
                'controller' => null
            ];

            $this->errorCode = $code;
        }else {
            //Se não for um codigo de erro valido.
            throw new RouterException("O codigo de erro <b>$code</b> é invalido.", 503);
        }
    }

    /**
     * Registra parametros proprios para serem passados ao erro.
     *
     * @param array $params
     * @return void
     */
    public function params(array $params) : void {
        self::$errors[$this->errorCode]['params'] = $params;
    }

    /**
     * Registra um controlador para o erro.
     */
    public function controller(string | callable $errorController) : void {
        $code = $this->errorCode;
        if($this->isValidController($errorController)) {
            //Se o controlador do erro for valido.

            if(!$this->errorHasController()) {
                //Se o erro não possui um controlador.

                self::$errors[$code]['controller'] = $errorController;
                return;
            }

            //Se o erro já possui um controlador.
            throw new RouterException("O erro <b>$code</b> já possui um controlador.", 302);
        }

        //Se o controlador do erro for invalido.
        throw new RouterException("O controlador do erro <b>$code</b> é invalido.", 303);
    }

    /**
     * Verifica se o controlador passado é valido.
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
     * Verifica se o erro atual possui um controlador.
     *
     * @return boolean
     */
    private function errorHasController() : bool {
        $error = self::$errors[$this->errorCode];
        if($error['controller'] === null) {
            //Se o erro não possui um controlador.
            return false;
        }
        return true;
    }


}

?>