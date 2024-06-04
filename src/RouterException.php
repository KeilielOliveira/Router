<?php 

namespace Router;

class RouterException extends \Exception {

    private string $fix; //Como corrigir a exceção.

    /**
     * Salva as informações da exceção.
     *
     */
    public function __construct(string $message, int $code = 0, string $fix = "") {
        parent::__construct($message, $code);

        $this->fix = $fix;
    }

    /**
     * Exibe a exceção.
     *
     * @return void
     */
    public function throw() {
        $code = $this->getCode();
        $message = $this->getMessage();
        echo "Ocorreu um erro: code[$code] $message<br><br>";

        //Se foi passada uma forma de corrigiri o erro.
        if($this->fix != "") {
            echo "Como corrigir: <br>" . $this->fix;
        }
    }

}

?>