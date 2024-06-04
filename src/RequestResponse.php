<?php 

namespace Router;

class RequestResponse extends RouterConfig {

    /**
     * Incializa as variaveis usadas pela classe.
     */
    public function __construct() {
        self::$responseContent = "";
    }

    /**
     * Defini o conteudo HTML a ser enviado como resposta da requisição.
     *
     * @param string $html
     * @return void
     */
    public function html(string $html) {
        self::$responseContent = $html;
    }

    /**
     * Exibe o conteudo definido.
     *
     * @return void
     */
    public function view() {
        $responseContent = preg_replace('/\{csrf_route_token\}/', $_SESSION['csrf_route_token'], self::$responseContent);
        echo $responseContent;
        return;
    }

}

?>