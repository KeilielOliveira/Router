<?php

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Router\Error\RouteError;
use Router\Router;
use Router\RouterException;

final class RouteErrorTest extends TestCase {

    /**
     * Testa se o sistema está validando corretamente os codigos de erro validos.
     *
     * @return void
     */
    #[DataProvider('errorCodesProvider')]
    public function testErrorCodeIsValidWithInvalidErrorCodes($code) : void {
        $this->expectException(RouterException::class);
        $this->expectExceptionCode(503);
        $this->expectExceptionMessage("O codigo de erro <b>$code</b> é invalido.");
        $e = new RouteError($code);
    }

    /**
     * Provedor de codigos de erros invalidos.
     *
     * @return array
     */
    public static function errorCodesProvider() : array {
        return [
            [200], [300], [542]
        ];
    }

    /**
     * Testa se o sistema de registro de erros está validando corretamente os controladores.
     *
     * @return void
     */
    public function testErrorControllerValidation() : void {
        $e = new RouteError(404);
        $reflectioMethod = new ReflectionMethod($e, 'isValidController');
        $reflectioMethod->setAccessible(true);

        //Teste com controladores validos.
        $this->assertTrue($reflectioMethod->invokeArgs($e, [function() {}]));
        $this->assertTrue($reflectioMethod->invokeArgs($e, ['Testes\ClasseDeTestes']));
        $this->assertTrue($reflectioMethod->invokeArgs($e, ['Testes\ClasseDeTestes@publicController']));
        $this->assertTrue($reflectioMethod->invokeArgs($e, ['Testes\ClasseDeTestes@staticController']));

        //Testes com controladores invalidos.
        $this->assertFalse($reflectioMethod->invokeArgs($e, ['function() {}']));
        $this->assertFalse($reflectioMethod->invokeArgs($e, ['Testes\ClasseDeTestesInvalida']));
        $this->assertFalse($reflectioMethod->invokeArgs($e, ['Testes\ClasseDeTestes@invalidController']));
        $this->assertFalse($reflectioMethod->invokeArgs($e, ['Testes\ClasseDeTestes@privateController']));
    }

    /**
     * Testa se o sistema está emitindo um erro quando um controlador é passado para um erro que já possui um.
     *
     * @return void
     */
    public function testVerifyErrorHasController() : void {
        $this->expectException(RouterException::class);
        $this->expectExceptionCode(302);
        $this->expectExceptionMessage("O erro <b>404</b> já possui um controlador.");
        $e = new RouteError(404);
        $e->controller(function() {});
        $e->controller(function() {});
    }
}

?>