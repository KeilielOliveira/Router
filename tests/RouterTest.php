<?php 

use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase {

    public function testGet() {

        $result = Router\Router::get('/', function($route) {
            $route->setParams([
                'teste' => 'Tesntando a asição de rotas.'
            ]);
            $route->setName('home');
        });
        $this->assertTrue($result);

    }

}

?>