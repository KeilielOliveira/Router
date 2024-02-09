<?php 

namespace Router;

class Debug {

    public static $isDebugMode = false; //Defini se está em modo de teste.
    public static $skipControllerValidate = false; //Defini se ira pular a validação dos controladores de rota.

    /**
     * Defini a classe Router para o modo de testes.
     *
     * @return self
     */
    public static function setDebugMode() {
        self::$isDebugMode = true;
        return new Debug();
    }

    /**
     * Defini se a classe Router irá pular a validação dos controladores de rotas.
     *
     * @return self
     */
    public function skipControllerValidate() {
        if(self::$isDebugMode) {
            self::$skipControllerValidate = true;
        }
        return $this;
    }

}

?>