<?php 

namespace Router;

use Exception;

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
        return;
    }

    /**
     * Inseri o HTML passado na frente do conteudo já registrado.
     *
     * @param string $html
     * @return void
     */
    public function insertHtml(string $html) {
        self::$responseContent .= $html;
        return;
    }

    /**
     * Recupera o conteudo de um arquivo e defini como conteudo da resposta.
     *
     * @param string $template
     * @return void
     */
    public function template(string $template) {
        try {
            if(file_exists($template)) {
                //Se o arquivo existir.
                self::$responseContent = file_get_contents($template);
                return;
            }
            throw new Exception("O template <b>$template</b> não foi encontrado!");
        }catch(Exception $e) {
            echo "Ocorreu um erro: " . $e->getMessage() . "<br><br>";
        }
    }

    /**
     * Defini um header para a resposta da requisição.
     *
     * @param string $header
     * @return void
     */
    public function header(string $header) {
        header($header);
        return;
    }

    /**
     * Retorna o conteudo já registrado.
     *
     * @return void
     */
    public function getHtml() {
        return self::$responseContent;
    }


    /**
     * Exibe o conteudo definido.
     *
     * @return void
     */
    public function view() {
        $csrfToken = $_SESSION['csrf_route_token'];
        $responseContent = self::$responseContent;
        $responseContent = preg_replace('/\{csrf_route_token\}/', $csrfToken, $responseContent);
        echo $responseContent;
        return;
    }

}

?>