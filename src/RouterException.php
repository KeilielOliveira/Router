<?php 

namespace Router;

class RouterException extends \Exception {

    private string $fix; //Como corrigir a exceção.
    private string $route; //Rota em que o erro ocorreu.
    private string $requestMethod; //Metodo de requisição da rota em que o erro ocorreu.
    private string $action; //Ação sendo executada que levou ao err.

    /**
     * Salva as informações da exceção.
     *
     */
    public function __construct(string $message, int $code = 0, string $fix = "") {
        parent::__construct($message, $code);
        $this->fix = $fix;
        $this->route = "";
        $this->requestMethod = "";
        $this->action = "";
    }

    /**
     * Adiciona a rota em que ocorreu o erro na mensagem.
     *
     * @param string $route
     * @return void
     */
    public function route(string $route) : void {
        $this->route = $route;
    }

    /**
     * Adiciona o metodo de requisição HTTP da rota em que o erro ocorreu na mensagem.
     *
     * @param string $requestMethod
     * @return void
     */
    public function requestMethod(string $requestMethod) : void {
        $this->requestMethod = $requestMethod;
    }

    /**
     * Adiciona a ação sendo executada quando o erro ocorreu na mensagem.
     *
     * @param string $action
     * @return void
     */
    public function action(string $action) : void {
        $this->action = $action;
    }

    /**
     * Exibe a exceção.
     *
     * @return void
     */
    public function throw() {
        $code = $this->getCode();
        $route = $this->route;
        $requestMethod = $this->requestMethod;
        $action = $this->action;

        $message = "code[$code]";
        $message .= $route == "" ? "" : " route[$route]";
        $message .= $requestMethod == "" ? "" : " http request method[$requestMethod]";
        $message .= $action == "" ? "" : " current action[$action]";
        $message .= "<br>Erro: " . $this->getMessage() . "<br>";

        echo "Ocorreu um erro: " . $message;

        //Se foi passada uma forma de corrigiri o erro.
        if($this->fix != "") {
            echo "Como corrigir: " . $this->fix . '<br>';
        }
    }

}

?>