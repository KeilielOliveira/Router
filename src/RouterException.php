<?php 

namespace Router;

class RouterException extends \Exception {

    /**
     * Conteudo adicional da mensagem de erro.
     *
     * @var array
     */
    private array $additionalContent;

    /**
     * Salva as informações da exceção.
     *
     */
    public function __construct(string $message, int $code = 0) {
        parent::__construct($message, $code);
        $this->additionalContent = [];
    }

    /**
     * Conteudo adicional da mensagem de erro.
     *
     * @param array $content
     * @return void
     */
    public function additionalContent(array $content) : void {
        $this->additionalContent = $content;
    }


    /**
     * Adiciona a rota em que ocorreu o erro na mensagem.
     *
     * @param string $route
     * @return void
     */
    public function route(string $route) : void {

    }

    /**
     * Adiciona o metodo de requisição HTTP da rota em que o erro ocorreu na mensagem.
     *
     * @param string $requestMethod
     * @return void
     */
    public function requestMethod(string $requestMethod) : void {

    }

    /**
     * Adiciona a ação sendo executada quando o erro ocorreu na mensagem.
     *
     * @param string $action
     * @return void
     */
    public function action(string $action) : void {

    }

    /**
     * Exibe a exceção.
     *
     * @return void
     */
    public function throw() {
        $code = $this->getCode();
        $message = $this->getMessage();

        //Monta as informações do erro.
        $head = "<p style='font-size:19px;'>Ocorreu um erro: code[$code] ";
        foreach ($this->additionalContent as $param => $value) {
            $head .= $param . "[ $value ] ";
        };
        echo "$head<br>Mensagem: $message</p>";
    }

}

?>