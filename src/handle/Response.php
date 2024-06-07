<?php 

namespace Router\Handle;

use Router\RouterException;
use Router\Router;

class Response {

    /**
     * Instancia da classe Request.
     *
     * @var Request
     */
    private Request $request;

    /**
     * Conteudo a ser adicionado antes do conteudo principal.
     *
     * @var string
     */
    protected static string $beforeContent;

    /**
     * Conteudo da resposta da requisição http.
     *
     * @var string
     */
    protected static string $content;

    /**
     * Conteudo a ser adicionado depois do conteudo principal.
     *
     * @var string
     */
    protected static string $afterContent;

    /**
     * Codigo de estatus da requisição.
     *
     * @var integer
     */
    protected static int $statusCode;

    /**
     * Defini se o CSRF token deve ser automaticamente substituido.
     *
     * @var boolean
     */
    protected static bool $useCsrfToken;


    /**
     * Inicia as variaveis da classe.
     */
    public function __construct(Request $request) {
        $this->request = $request;
        self::$content = "";
        self::$beforeContent = "";
        self::$afterContent = "";
        self::$useCsrfToken = true;
    }

    /**
     *Defini o conteudo da resposta da requisição.
     *
     * @param string $content
     * @return void
     */
    public function setContent(string $content) : void {
        self::$content .= $content;
    }

    /**
     * Adiciona conteudo na resposta da requisição antes do conteudo já existente.
     *
     * @param string $beforeContent
     * @return void
     */
    public function setBeforeContent(string $beforeContent) : void {
        self::$beforeContent .= $beforeContent;
    }

    /**
     * Adiciona conteudo na resposta da requisição após o conteudo já existente.
     *
     * @param string $afterContent
     * @return void
     */
    public function setAfterContent(string $afterContent) : void {
        self::$afterContent .= $afterContent;
    }

    /**
     * Defini headers para a resposta da requisição http.
     *
     * @param string $header
     * @return void
     */
    public function setHeader(array $headers) : void {
        try {
            if(count($headers) > 0) {
                //Se foi passado pelo menos um header.
                foreach($headers as $name => $header) {
                    //Percorre cada header.
                    if(is_string($name) && is_string($header)) {
                        //Se ambos forem strings.
                        header($name . ': ' . $header);
                        return;
                    }else {
                        $exception = new RouterException("O header <b>$name</b> é invalido.", 200);
                        $exception->action("Registro de headers");
                        throw $exception;
                    }
                }
            }
            $exception = new RouterException("Pelo menos um header deve ser passado.", 204);
            $exception->action("Registro de headers");
            throw $exception;
        }catch(RouterException $e) {
            $e->throw();
        }
    }

    /**
     * Defini o codigo de estatatus da resposta.
     *
     * @param integer $code
     * @return void
     */
    public function setStatusCode(int $code) : void {
        http_response_code($code);
        self::$statusCode = $code;
    }

    /**
     * Recupera o conteudo adicionado.
     *
     * @return string
     */
    public function getContent() : string {
        return self::$content;
    }

    /**
     * Recupera o conteudo a ser adicionado antes do conteudo principal.
     *
     * @return string
     */
    public function getBeforeContent() : string {
        return self::$beforeContent;
    }

    /**
     * Recupera o conteudo a ser adicionado após o conteudo principal.
     *
     * @return string
     */
    public function getAfterContent() : string {
        return self::$afterContent;
    }

    /**
     * Recupera todo o conteudo já registrado.
     *
     * @return string
     */
    public function getAllContent() : string {
        $content = self::$beforeContent . self::$content . self::$afterContent;
        return $content;
    }

    /**
     * Recupera os headers registrados para a rota.
     *
     * @return array
     */
    public function getHeaders() : array {
        return get_headers($this->request->url());
    }

    /**
     * Recupera o codigo de estatus da requisição.
     *
     * @return integer
     */
    public function getStatusCode() : int {
        $statusCode = self::$statusCode === null ? 0 : self::$statusCode;
        return $statusCode;
    }

    /**
     * Remove o header passado.
     *
     * @param string $name
     * @return void
     */
    public function removeHeader(string $name) : void {
        header_remove($name);
    }

    /**
     * Disabilita o uso automatico do CSRF token.
     *
     * @return void
     */
    public function disableCsrfToken() : void {
        self::$useCsrfToken = false;
        return;
    }

    /**
     * Envia o conteudo da resposta.
     *
     * @return void
     */
    public function send() : void {
        $content = self::$beforeContent . self::$content . self::$afterContent;
        if(self::$useCsrfToken) {
            //Se for para usar o CSRF token automaticamente.
            $token = $this->request->csrfToken();
            $content = preg_replace('/\{csrf_token\}/', $token, $content);
        }
        echo $content;
    }
}

?>