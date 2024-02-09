<?php 

namespace Router;

class Validate {

    /**
     * Verifica se a URI passada é valida.
     *
     * @param string $uri: A URI a ser validada.
     * @return boolean
     */
    public function isValidUri(string $uri) {
        $p = "([a-zA-Z0-9-_]+|\{[a-zA-Z0-9-_]+(\=.+)?\})";
        $i = "\:[a-zA-Z0-9-_]+(\&[a-zA-Z0-9-_]+)*";
        $regexp = "/^\/($p(\/$p)*)?($i)?$/U";

        //Se a URI for valida.
        if(preg_match($regexp, $uri)) {
            return true;
        }

        return false;
    }

}

?>