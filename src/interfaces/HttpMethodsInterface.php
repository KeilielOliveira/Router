<?php 

namespace Router\Interfaces;

interface HttpMethodsInterface {

    public function get(string $route, callable $callback);

}

?>