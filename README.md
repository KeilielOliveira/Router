# Router

  

Um sistema de roteamento php que visa permitir o controle das **URLS** acessiveis com base no registro de rotas.

Abaixo segue-se um resumo sobre os principais metodos e como usa-los.

  

# Metodos
  

### get(), post(), put(), delete() e patch()

São metodos de registro de rotas para os respectivos tipos de requisições HTTP.

#### Exemplo de uso

---  

```php

<?php

  

Router\Router::get('/', function($route) {

  

return  $route->return();

});

  

?>

```

#### Parametros

  

-  **$route** (String): A URL da rota, pode ser definida de diversas formas como literal, com parametros e com parametros GET, durante o registro elas são convertidas em expressões regulares para validação.

-  **$callback** (Callable): Função de callback que recebe uma instancia com os metodos de configuração da rota.

  

### Exemplos

-  **$route**: Abaixo está uma lista de exemplos de URLS validas no formato URL => Expressão regular.

- / => \/

- /home => \/home

- /home/blog => \/home\/shop

- /{page} => \/[a-zA-Z0-9-_]+

- /{page=numeric} => \/[0-9-_]+

- /{page=(blog|shop)} => \/(blog|shop)

- /:parametro_get => \/

- /{page}:parametro_get_1&parametro_get_2 => \/[a-zA-Z0-9-_]+

  

-  **$callback**: Exemplo de uma função de callback valida.

```php

<?php

$callback = function($route) {

  

return  $route->return();

};

?>

```
<br><br><br>
### setName()

Permite definir um nome personalizado para uma rota.

  

#### Exemplo de uso

  

---

  

```php

<?php

Router\Router::get('/', function($route) {

$route->setName('Home');

  

return  $route->return();

});

?>

```

### Parametros

  

-  **$name** (String): O nome personalizado da rota.
<br><br><br>
### setParams()

Registra nas configurações da rota um array com parametros que podem ser acessados em middlewares e controladores da rota.

#### Exemplo de uso 

---
```php

<?php

Router\Router::get('/', function($route) {

$route->setParams([

'user'  =>  'Admin',

'id'  =>  12

]);

  

return  $route->return();

});

?>

```

### Parametros

-  **$params** (Array): Um array com qualquer tipo de valor.

<br><br><br>

### controller()

Defini um controlador para a rota, pode ser uma função ou classe.

### Exemplo de uso

---


```php

<?php

Router\Router::get('/', function($route) {

$route->controller('Controlador');

  

return  $route->return();

});

?>


```
A função ou metodo do controlador deve sempre receber dois parametros.
#### Parametros do controlador
-  **$req** (Array): Contem todas as informações da rota.
-  **$res** (Response): A instancia da classe **Response** que é usada para definir a resposta da requisição.

<br>

### Parametros

-  **$controller** (String|Callable): Uma função ou classe que sera executada quando a rota for acessada.

### Exemplos

-  **$controller**: Abaixo estão listados os formatos aceitos de controlador.

	- MeuControlador => Executa o __invoke() da classe passada.

	- MeuControlador@metodo => Executa o metodo() da classe passada.

	- function() {} => Executa a função passada.

<br><br><br>

### Middlewares()
Permite a adição de middlewares que são divididos em dois tipos, os **before middleware** são os middlewares executados antes do controlador da classe e os **after middlewares** são os middlewares executados após o controlador.

#### Exemplo de uso

---

```php 
<?php
	Router\Router::get('/', function($route) {
	$route->middlewares(function($middleware) {
		$middleware->before('MeuBeforeMiddleware')
		->after([
			'MeuAfterMiddleware@middleware',
			function() {}
		]);
		return $middleware->return();
	});
	
	return $route->return();
	});
?>
```

### Parametros

- **$callback** (Callable): Uma função de callback que recebe a instancia da classe de registro de middlewares.

<br><br><br>

### before(), after()
São metodos de registro de middlewares que representam respectivamente os middlewares que serão executados antes do controlador e os middlewares que serão executados após o controlador.

#### Exemplo de uso

---

```php 
<?php
	Router\Router::get('/', function($route) {
	$route->middlewares(function($middleware) {
		$middleware->before('MeuBeforeMiddleware')
		->after([
			'MeuAfterMiddleware@middleware',
			function() {}
		]);
		return $middleware->return();
	});
	
	return $route->return();
	});
?>
```

### Parametros
-  **$middlewares** (String | Callable | Array): Os middlewares seguem o mesmo padrão do controlador, a principal diferença é a possibilidade de registrar multiplos middlewares, eles são executados na ordem em que são passados e devem seguir uma determinada sintaxe.

### Exemplos

- **$middlewares**: Todos os middlewares devem receber 3 parametros como exemplificado abaixo e todos devem ter um retorno para definir se a execução da rota deve ou não prosseguir.
	
	##### Parametros dos middlewares
	-  **$req** (Array): Contem todas as informações da rota.
	-  **$res** (Response): A instancia da classe **Response** que é usada para definir a resposta da requisição.
	- **$next** (Callable): Uma função usada para permitir a execução do proximo middleware caso exista, todos os middlewares devem obrigatoriamente possuir um retorno, senão o sistema irá considerar que a execução falhou e deve ser invalidada.
 
```php
<?php

$middleware = function($req, $res, $next) {

	return $next($req); // Ou simplesmente return true;
}

?>
```

Os middlewares também podem ser no formato.

- MeuMiddleware => Executa o __invoke() da classe.
- MeuMiddleware@middleware => Executa o metodo **middleware()** da classe.
- function() {} => Executa a função em si.

<br><br><br>

### return()
O metodo return é usado para retornar o conteudo gerado, todas as funções de callback devem retornar esses metodo.

#### Exemplo de uso

---

```php 
<?php 

Router\Router::get('/', function($route) {

	$route->middlewares(function($middleware) {

		return $middleware->return();
	});

	return $route->return();
});

?>
```

<br><br><br>

### group()
Permite a criação de um grupo de rotas.

#### Exemplo de uso

---

```php 
<?php 

Router\Router::group(function($group) {
	$group->get('/', function($route) {

		return $route->return();
	});
	
	return $group->return();
});

?>
```

O **group()** possui todos os metodos de registro de rotas, esses metodos seguem a mesma sintaxe dos metodos padrões de registro, permitindo registro de nomes, parametros, controladores e middlewares da mesma forma.

### Parametros

- **$callback** (Callable): Função de callback que recebe uma instancia de classe com todos os metodos de configuração de um grupo de rotas.

<br><br><br>

### prefix()
Defini um prefixo que será adicionado ao inicio de todoas as rotas registradas no grupo.

#### Exemplo de uso

---

```php 
<?php 

Router\Router::group(function($group) {
	$group->prefix('/account');

	//A URL dessa rota será /account/user
	$group->get('/user', function($route) {
	
		return $route->return();
	});
	
	return $group->return();
});

?>
```

### Parametros 

- **$prefix** (String): O prefixo a ser adicionado no grupo.


<br><br><br>

### groupController()
Registra um controlador para todas as rotas do grupo.

#### Exemplo de uso

---

```php 
<?php 

Router\Router::group(function($group) {
	$group->groupController('MeuControlador');

	$group->get('/', function($route) {

		return $route->return();
	});
	
	return $group->return();
});

?>
```
Caso as rotas possuam um controlador individual o controlador de grupo será colocado no lugar.


### Parametros

-  **$controller** (String|Callable): Uma função ou classe que sera executada quando a rota for acessada.

### Exemplos

-  **$controller**: Abaixo estão listados os formatos aceitos de controlador.

	- MeuControlador => Executa o __invoke() da classe passada.

	- MeuControlador@metodo => Executa o metodo() da classe passada.

	- function() {} => Executa a função passada.

<br><br><br>

### groupMiddlewares()
Registra middlewares que serão adicionados em todas as rotas, esses middlewares serão adicionados executados primeiro e somente após os middlewares individuais das rotas.

#### Exemplo de uso

---

```php 
<?php 

Router\Router::group(function($group) {
	$group->groupMiddlewares(function($middleware) {

	return $middleware->return();
	});

	$group->get('/', function($route) {

		return $route->return();
	});
	
	return $group->return();
});

?>
```

### Parametros
- **$callback** (Callable): Uma função de callback que recebe a instancia da classe de registro de middlewares, a sintaxe e os metodos dessa instancia são as mesmas do metodo **middlewares()** descrito anteriormente.

<br><br><br>

### globalMiddlewares()
Registra middlewares que serão usados por todas as rotas registradas.

#### Exemplo de uso

---

```php 
<?php 

Router\Router::globalMiddlewares(function($middleware) {

	return $middleware->return();
});

?>
```

### Parametros
- **$callback** (Callable): Uma função de callback que recebe a instancia da classe de registro de middlewares, a sintaxe e os metodos dessa instancia são as mesmas do metodo **middlewares()** descrito anteriormente.

<br><br><br>

### error()
Defini um erro personalizado que pode ser lançado atraves de excessões em middlewares ou controladores.

#### Exemplo de uso

---

```php
<?php

Router\Router::get('/', function($route) {
	$route->controller(function($req, $res) {
		throw new Exception('Ocorreu um erro!', 100);
	});

	return $route->return();
});

Router\Router::error(100, function($params) {
	//Codigo que lida com o erro.
});

?>
```
Caso não seja registrado um controlador para o erro, o sistema irá exibir o codigo do erro e a mensagem da Exception lançada.
Erro 100: Ocorreu um erro!

### Parametros
- **$code** (Int): O codigo do erro.
- **$controller** (String | Callable): O controlador do erro.
- **$params** (Array): Parametros opcionais que serão passados para o controlador do erro. 

<br><br><br>

### handleRoutes()
Lida com a execução das rotas, procurando a rota que bate com as informações da requisição.

#### Exemplo de uso

---

```php 
<?php 
Router\Router::handleRoute();
?>
```

#### Logica do metodo

 1. Recupera todas as rotas que foram registradas no metodo de requisição HTTP atual.
 2.  Pega a URL da pagina e procura uma rota que bata com esta URL.
 3. Verifica se a requisição atual possui todos os parametros GET definidos na rota.
 4. Recupera os parametros da rota e coloca dentro das definições da rota.
 5. Executa os before middlewares, se nenhum deles retornar um erro segue.
 6. Executa o controlador da rota.
 7. Executa os after middlewares da rota, se nenhum deles retornar um erro segue.
 8. Por fim retorna a resposta gerada pela classe **Response** para o navegador.

