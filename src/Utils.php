<?php 

namespace Router;

class Utils {

    /**
     * Prepara o $arg retornando informações sobre o mesmo.
     *
     * @param string $arg: A função ou classe a ser preparado.
     * @return array
     */
    public function prepareClassOrFunction(string|callable $arg) {
        $response = [];
        if(is_callable($arg)) {
            //Se for uma função.
            $response = [
                'type' => 'function',
                'function' => $arg
            ];
        }else {
            //Se for uma classe com ou sem metodo.
            if(str_contains($arg, '@')) {
                [$class, $method] = explode('@', $arg);
                $response = [
                    'type' => 'class',
                    'class' => $class,
                    'method' => $method
                ];
            }else {
                $response = [
                    'type' => 'class',
                    'class' => $arg,
                ];
            }
        }

        return $response;
    }

}

?>