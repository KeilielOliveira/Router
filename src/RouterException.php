<?php 

namespace Router;

class RouterException extends \Exception {

    /**
     * Possiveis erros.
     *
     * @var array
     */
    private array $codes;

    /**
     * Salva as informações da exceção.
     *
     */
    public function __construct(string $message, int $code = 0) {
        parent::__construct($message, $code);
        $this->init();
    }

    /**
     * Incia os erros do sistema.
     *
     * @return void
     */
    private function init() : void {
        $this->codes = [
            101 => 'Erro desconhecido.',
            102 => 'Rota já registrada.',
            103 => 'Rota invalida.',
            104 => 'Rota inexistente.',
            105 => 'Sintaxe da rota invalida.',
            201 => 'Erro desconhecido.',
            202 => 'Grupo já registrado.',
            203 => 'Grupo invalido.',
            204 => 'Grupo inexistente.',
            205 => 'Sintaxe do grupo invalida.',
            301 => 'Erro desconhecido.',
            302 => 'Controlador já foi registrado.',
            303 => 'Controlador invalido.',
            304 => 'Controlador inexistente.',
            305 => 'Sintaxe do controlador invalida.',
            401 => 'Erro desconhecido.',
            402 => 'Middleware já registrado.',
            403 => 'Middleware invalido.',
            404 => 'Middleware inexistente.',
            405 => 'Sintaxe do middleware invalida.',
        ];
    }

    /**
     * Exibe a exceção.
     *
     * @return void
     */
    public function throw() {
        $code = $this->getCode();
        $message = $this->getMessage();
        $error = $this->codes[$code];

        //Monta as informações do erro.
        $head = "<p style='font-size:19px;'>Ocorreu um erro: code[$code] $error";
        echo "$head<br>Mensagem: $message</p>";
        die();
    }

}

?>