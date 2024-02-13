<?php 

namespace Router;

use Exception;

class Response {

    //Armazena informações da classe.
    private $content, $headers, $statusCode; 

    public function __construct() {
        $this->headers = [];
        $this->statusCode = 200;
    }

    /**
     * Defini o conteudo que será retornado ao navegador.
     *
     * @param string $content: O conteudo que será retornado.
     * @return void
     */
    public function setContent(string $content) {
        $this->content = $content;
        return;
    }

    /**
     * Defini o header principal da resposta.
     *
     * @param string $name: O header.
     * @param string $value: O valor do header.
     * @return void
     */
    public function setHeader(string $name, string $value) {
        $this->headers[] = "$name: $value";
        return;
    }

    /**
     * Defini o status code da requisição.
     *
     * @param integer $statusCode: O codigo de estatus.
     * @return void
     */
    public function setStatusCode(int $statusCode = 200) {
        $this->statusCode = $statusCode;
        return;
    }

    /**
     * Defini um cookie.
     *
     * @param string $name: Nome do cookie.
     * @param string $value: Valor do cookei.
     * @param integer $expire: Em quanto tempo o cookie expira.
     * @param string $path: O diretorio em que o cookie existe.
     * @param string $domain: O dominio em que o cookie estará disponivel.
     * @param boolean $secure: Defini que o cookie so será acessivel em uma requisição HTTPS.
     * @param boolean $httponly: Defini que o cookie será acessível somente sob o protocolo HTTP.
     * @return void
     */
    public function setCookie(string $name, string $value, int $expire = 0, string $path = '/', string $domain = '', bool $secure = false, bool $httponly = false) {
        setcookie($name, $value, $expire, $path, $domain, $secure, $httponly);
        return;
    }

    /**
     * Redireciona para a url passada.
     *
     * @param string $url: A url de redirecionamento.
     * @param integer $statusCode: O codigo de estatus.
     * @return void
     */
    public function redirect(string $url, int $statusCode = 302) {
        header("Location: $url", true, $statusCode);
        return;
    }

    /**
     * Adiciona headers extras para a respota.
     *
     * @param string $name: O header.
     * @param string $value: O valor do header.
     * @return void
     */
    public function addHeader(string $name, string $value) {
        try {
            if(!empty($this->headers)) {
                $this->headers[] = "$name: $value";
                return;
            }
            throw new Exception('Defina o header principal antes de adicionar multiplos headers!');
        }catch(Exception $e) {
            echo $e->getMessage();
            echo '<br><hr><br>'; 
        }
    }

    public function send() {
        try {
            if(!empty($this->headers)) {
                header(array_shift($this->headers), true, $this->statusCode);
                foreach ($this->headers as $key => $value) {
                    header($value);
                }
            } 
    
            echo $this->content;
        }catch(Exception $e) {
            echo $e->getMessage();
            echo '<br><hr><br>';
        }
    }

    public function reset() {
        unset($this->content, $this->headers, $this->statusCode);
        $this->statusCode = 200;
        return;
    }

    /**
     * Retorna o conteudo gerado para a pagina.
     *
     * @return string
     */
    public function getContent() {
        return $this->content;
    }

    /**
     * Retorna os headers da requisição.
     *
     * @return array
     */
    public function getHeaders() {
        return $this->headers;
    }

    /**
     * Retorna o codigo de status da requisição.
     *
     * @return int
     */
    public function getStatusCode() {
        return $this->statusCode;
    }
}

?>