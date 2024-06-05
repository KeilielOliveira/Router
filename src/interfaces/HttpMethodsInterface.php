<?php 

namespace Router\Interfaces;

interface HttpMethodsInterface {

    /**
     * Registra uma rota do tipo GET.
     *
     * @param string $route
     * @param callable $callback
     * @return void
     */
    public function get(string $route, callable $callback) : void;

    /**
     * Registra uma rota do tipo POST
     *
     * @param string $route
     * @param callable $callback
     * @return void
     */
    public function post(string $route, callable $callback) : void;

    /**
     * Registra uma rota do tipo PUT.
     *
     * @param string $route
     * @param callable $callback
     * @return void
     */
    public function put(string $route, callable $callback) : void;

    /**
     * Registra uma rota do tipo DELETE.
     *
     * @param string $route
     * @param callable $callback
     * @return void
     */
    public function delete(string $route, callable $callback) : void;

    /**
     * Registra uma rota do tipo UPDATE.
     *
     * @param string $route
     * @param callable $callback
     * @return void
     */
    public function update(string $route, callable $callback) : void;

    /**
     * Registra uma rota do tipo PATCH.
     *
     * @param string $route
     * @param callable $callback
     * @return void
     */
    public function patch(string $route, callable $callback) : void;

}

?>