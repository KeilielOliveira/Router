<?php 

namespace Router;

use Exception;

class Error {

    public static $errors; //Armazena as informações dos erros registrados.

    /**
     * Executa o controlador do erro passado.
     *
     * @param int $code: O codigo do erro.
     * @param string $message: Mensagem do erro (só será exibida caso nenhum controlodaro tenha sido definido para o erro).
     */
    public function __construct(int $code = 0, string $message = '') {
        if($code != 0) {
            if(isset(self::$errors[$code])) {
                $utils = new Utils();
                [$controller, $params] = self::$errors[$code];
                $utils->executeClassOrFunction($controller, [$params]);
            }else {
                echo "Erro $code: $message";
            }
        }
    }

    /**
     * Registra um erro.
     *
     * @param int $code: O codigo do erro.
     * @param string|callable $controller: O controlodaor do erro.
     * @param array $params: Parametros opcionais que serão passados para o controlador.
     * @return void
     */
    public function setError(int $code, string|callable $controller, array $params = []) {
        try {
            $validate = new Validate();
            $utils = new Utils();
            if($validate->isValidClassOrFunction($controller)) {
                //Se o controlador for valido.
                $controller = $utils->prepareClassOrFunction($controller);
                self::$errors[$code] = [$controller, $params];
                return;
            }
            throw new Exception("O controlador do erro <b>$code</b> não é valido!");
        }catch(Exception $e) {
            echo $e->getMessage();
            echo '<br><hr><br>';
        }
    }

}

?>