# Router
Uma classe de roteamento PHP simples.
Essa classe não está 100% concluida ou otimizada, tendo como finalidade o uso simples de um sistema de roteamento.

Abaixo segue-se uma documentação sobre o uso do sistema.

# Registro de rotas

O sistema possui suporte para registro de rotas para os principais metodos de requisição http como GET, POST, PUT, DELETE, UPDATE, PATCH. As rotas podem ser registradas através de metodos de mesmos nomes.

**Exemplo:** Esse é o exemplo de um registro de rota do tipo GET.
```php 
<?php 

$router = new Router\Router;

//Também poderia ser post(), put(), delete(), update() ou patch().
$router->get('/', function() {

});

?>
```

Cada um dos metodos de registro possui dois parâmetros obrigatorios.

- string **$route**: A rota sendo registrada.
- callable **$callback**: Função de callback onde as configurações da rota ocorrem.

**Oberservações:** O prametro **$callback** receber uma instancia da classe **RouteMethods** que contém todos os metodos de configurações de rotas.

## Sintaxe das rotas

As rotas possuem uma sintaxe propria permitindo bastante flexibilidade no registro das mesmas, elas podem ser passadas de três formas.

- **Literal**: As rotas literais como o proprio nome diz, é basicamente a url de onde a requisição deve vir.
- **Indefinida**: As rotas indefinidas permitem maior flexibilidade, já que ao invés de passar a url, ela permite a passagem de expressões regulares.
- **Parametros GET**: Esse é basicamente um adicional, também é possivel passar parametros GET que a requisição feita deve possuir.

**Exemplo:** Abaixo segue-se um exemplo de registro de cada uma das rotas.
```php 
<?php 

$router = new Router\Router;

//Rota literal.
$router->get('/home', function() {});

//Rota indefinida.
$router->get('/{page}', function() {});

//Rota com parametros GET.
$router->get('/:param', function() {});

?>
```

### Rotas indefinidas

As rotas indefinidas como apontado anteriormente, permitem bastante flexibilidade para registrar rotas. Elas basicamente seguem um padrão em sua sintaxe **{nome separador expressão regular}**.

- **nome**: O nome é basicamente um nome qualquer, ele irá servir de referencia para recuperar o valor dessa parte na execução da rota. Uma observação é que não deve haver nomes iguais.
- **separador**: Essa parte basicamente sepra o nome da expressão regular.
- **expressão regular**: A expressão regular customizada.

Nas rotas indefinidas há duas possibilidades. A primeira é sobre as partes ocultas da rote que so possuem o nome, como **/{page}**, neste caso a parte oculta será substituida por uma expressão regular padrão **[a-zA-Z0-9-_]+**. Na segunda possibilida, seria a passagem de uma expressão regular propria, como **/{page:(home|blog)}**, neste caso a parte oculta será substituida pela expressão regular customizada. Com relação aos separadores, eles são dois, : ou =, qualquer um deles pode ser usado para separar o nome da expressão regular.

### Parametros GET na rota

Os parametros GET na rota, permitem um maior controle sobre quais rotas são validas. Em resumo pode-se passar quantos quiser em uma rota usando o separador &, como em **/:id&token&user**.

## Configurações das rotas

As rotas como mencionado anteriormente, podem receber algumas configurações através da funçãod e callback. Essas configurações permitem a definição de parametros, middlewares e controladores para a rota.

**Exemplo:** Exemplo de uma rota com todas as configurações.
```php
<?php

$router = new Router\Router;

$router->get('/', function(RouteMethods $route) {

    $route->params(['um parametro qualquer']);
    $route->beforeMiddlewares(function() {});
    $route->controller(function() {});
    $route->afterMiddleware(function() {});

});

?>
```

No exemplo acima a rota recebe todas as configurações. As unicas configurações que serão mencionadas por hora são os parametros e o controlador.

### params()

Este metodo permite a passagem de um array contendo qualquer coisa, esse array será acessivel dentro dos controladores e middlewares.

### controller()

Este metodo permite o registro de um controlador para uma rota, cada rota pode possuir apenas um controlador que é basicamente o centro da logica de execução da rota. Os controladores podem ser passados de 3 formas.

1. Os controladores podem ser passados como uma função.
2. Os controladores podem ser passados como o namespace de uma classe, neste caso o sistema irá automaticamente buscar o metodo **controller()** dentro dessa classe, se ele não encontrar ocorrerá um erro.
3. Os controladores podem ser passados como o namespace de uma classe com um metodo especifico, dessa forma o sistema irá buscar o metodo especificado.

**Exemplo:** Abaixo segue-se exemplos de registro dos 3 tipos de controladores.
```php
<?php

$router = new Router\Router;

$router->get('/', function(RouteMethods $route) {

    //Controlador em formato de função.
    //$route->controller(function() {});

    //Controlador em formato de classe.
    //$route->controller('MinhaClasse');

    //Controlador em formato de classe com metodo.
    $route->controller('MinhaClasse@metodoControlador');

});

?>
```

## Grupos de rotas

Também é possivel registrar multiplas rotas sobre um prefixo de rota usando os grupos de rotas.

**Exemplo:** Exemplo de um grupo de rotas.
```php
<?php

$router = new Router\Router;

$router->group('/admin', function(RouteGroup $group) {

    //A rota aqui será: /admin/dashboard
    $group->get('/dashboard', function(RouteMethods $route) {

    });

});

?>
```

O metodo **group()** possui dois parametros, o primeiro é basicamente o prefixo do grupo e o seundo uma função de callback que recebe a instancia da classe **RouteGroup** onde as rotas e as configurações do grupo são definidas. Os grupos de rotas possuem apenas uma configuração, que são os middlewares de grupo que serão abordados posteriormente. Por fim ele possui os mesmos metodos de registro de rotas, que seguem a mesma logica dos anteriores.

# Middlewares

Os middlewares são bem convenientes para lidar com validações e tarefas separadas da logica principal da rota. Esse sistema possibilita 3 formas de registrar middlewares.

1. **Middlewares globais**: São os middlewares que são executados em todas as rotas registradas.
2. **Middlewares de grupos**: São os middlewares executados em todas as rotas registradas em um grupo de rotas.
3. **Middlewares de rotas**: São os middlewares individuais de cada rota.

Esses middlewares são divididos em 2 categorias.

- **Before middlewares**: São os middlewares executados antes do controlador da rota.
- **After Middlewares**: São os middlewares executados após o controlador da rota.

Os middlewares seguem a mesma logica dos controladores no registro, a somente duas diferenças.

1. Quando um middleware é passado como o namespace de uma classe, o metodo buscado por padrão é o **middleware()**.
2. É possivel passar um array contendo multiplos middlewares.

No caso dos middlewares globais, todos os middlewares devem ser passados em um array.

## Metodos de registro de middlewares

Como mencionado anteriormente, há 3 formas de registrar middlewares, sendo elas os metodos:

### globalMiddlewares()

Esse é o metodo usado para registrar middlewares globais, ele recebe dois parametros.

- string **$middlewareType**: Defini o tipo dos middlewares, só são aceitos os valores **before** e **after**.
- array **$globalMiddlewares**: Os middlewares em si.

**Exemplo:** Abaixo um exemplo do registro de middlewares globais.
```php
<?php

$router = new Router\Router;

$router->globalMiddlewares('before', [function() {}]);
$router->globalMiddlewares('after', [function() {}]);

?>
```

### beforeGroupMiddlewares() e afterGroupMiddlewares()

Esses metodos registram middlewares de grupo, aos quais só podem ser registrados dentro de um grupo de rotas. Ao contrario dos **middlewares globais**, aqui há um metodo para cada tipo de middleware, deixando um unico parametro a ser passado, os middlewares.

- string | callable | array **$groupMiddlewares**: Os middlewares a serem registrados.

**Exemplo:** Abaixo um exemplo do registro de middlewares de grupo.
```php
<?php

$router = new Router\Router;

$router->globalMiddlewares('before', [function() {}]);
$router->globalMiddlewares('after', [function() {}]);

$router->group('/admin', function(RouteGroup $group) {

    $group->beforeGroupMiddlewares(function() {});
    $group->afterGroupMiddlewares([function() {}]);

});

?>
```

### beforeMiddlewares() e afterMiddlewares()

Esses metodos registram os middlewares individuais de cada rota, portanto só podem ser chamados dentro das configurações de uma rota, eles seguem a mesma logica dos **middlewares de grupo**.

**Exemplo:** Abaixo um exemplo do registro de middlewares de rota.
```php
<?php

$router = new Router\Router;

$router->globalMiddlewares('before', [function() {}]);
$router->globalMiddlewares('after', [function() {}]);

$router->group('/admin', function(RouteGroup $group) {

    $group->beforeGroupMiddlewares(function() {});
    $group->afterGroupMiddlewares([function() {}]);

    $grou->get('/dashboard', function(RouteMethods $route) {

        $route->beforeMiddlewares('MinhaClasse'); //Irá buscar o metodo middleware() na classe MinhaClasse.
        $route->afterMiddlewares('MinhasClasse@metodoControlador'); //Irá buscar o metodo metodoControlador() na classe MinhaClasse.

    });
});

?>
```

## Observações

Abaixo segue-se algumas observações importantes sobre os middlewares.

### Execução dos middlewares

Os middlewares são executados na ordem em quem são passados e também na seguinte ordem: **middlewares de rota**, **middlewares globais** e **middlewares de grupo**.

### Funcionalidade

Os middlewares também podem ser usados para barrar a execução de uma rota, onde caso um dos middlewares retorne **false** todos os middlewares subsequentes e o controlador, não serão executados.

# Request e Response

Os middlewares e controladores recebem dois parametros.

1. Instancia da classe Request que contém todos os dados da requisição.
2. Instancia da classe Response usada para mandar uma resposta.

Abaixo segue-se uma explicação de cada um dos metodos de ambas as classes.

## Request

### requestMethod()

Recupera o metodo http da requisição feita.

### url()

Recupera a url completa da requisição.

### requestId()

Recupera o id do dispositivo que fez a requisição.

### urlHiddenParams()

Recupera os valores das partes indefinidas da rota. Possui um parametro, que é o nome da parte a ser retornada, se não for passada irá retornar um array contendo todos os valores.

### queryParams()

Recupera os parametros GET da passados na rota em formato de array.

### params()

Recupera os parametros registrados nas configurações da rota. Possui um parametro, que é a chave do array a ser buscada, se não for passada irá retornar o array completo.

### csrfToken()

Recupera o CSRF token da rota.

## Response

### setContent()

Adiciona conteudo ao conteudo já existente ou defini o conteudo caso nenhum tenha sido definido anteriormente.

### setBeforeContent()

Adiciona conteudo ao conteudo já existente ou defini o conteudo caso nenhum tenha sido definido anteriormente. O before content será adicionado antes do conteudo principal ao ser enviado.

### setAfterContent()

Adiciona conteudo ao conteudo já existente ou defini o conteudo caso nenhum tenha sido definido anteriormente. O before content será adicionado após o conteudo principal ao ser enviado.

### setHeader()

Defini os headers para a resposta da requisição.

### setStatusCode()

Defini o codigo de status da resposta da requisição.

### getContent(), getBeforeContent() e getAfterContent()

Recuperam cada um seu respectivo conteudo.

### getAllContent()

Recupera todo o conteudo no formato em que será enviado.

### getHeaders()

Recupera os headers definidos na resposta da requisição.

### getStatusCode()

Recupera o codigo de status da resposta da requisição.

### removeHeader()

Remove o header cujo o nome for passado.

### disableCsrfToken()

Disabilita a substituição automatica das marcações pelo CSRF token.

### send()

Esse metodo envia a resposta, ele é automaticamente usado pelo sistema.

# Execução das rotas

Para o sistema executar as rotas basta chamar o metodo **handle()**.

**Exemplo:** Um exemplo de um sistema de rotas sendo executado.
```php
<?php

$router = new Router\Router;

$router->get('/{page}', function(RouteMethods $route) {

    $route->controller(function(Request $req, Response $res) {
        $page = $req->urlHiddenParams('page')
        $res->setContent('Bem vindo a pagina ' . $page);
    });

});

$router->handle()

?>
```

No exemplo acima ao acessar a pagina **/home** o sistema irá verificar se existe uma rota que bata com a requisição, neste caso existe, sendo assim ele executara o controlador retornando para a pagina a mensagem **Bem vindo a pagina home**.

# CSRF token

Ao executar uma rota o sistema automaticamente gera um csrf token para a rota, ele pode ser recuperado no objeto **Request**, o sistema também fornece uma substituição automatica da marcação **{csrf_token}** no conteudo da resposta pelo token da rota.

# Paginas de erros

Atualmente somente a pagina de erro 404 é suportada, ela não é previamente definida, deve ser regsitrada como as outras rotas.

**Exemplo:** O exemplo a seguir é uma demonstração de como registrar uma pagina de erro 404.
```php
<?php

$router = new Router\Router;

$router->get('/{page}', function(RouteMethods $route) {

    $route->controller(function(Request $req, Response $res) {
        $page = $req->urlHiddenParams('page')
        $res->setContent('Bem vindo a pagina ' . $page);
    });

});

$router->error(404, function(RouteError $e) {
    $e->controller(function(Request $req, Response $res) {
        $url = $req->url();
        $res->setContent("A url <b>$url</b> não é acessivel!");
    });
});
$router->handle()

?>
```

Dessa forma quando uma rota não registrada for acessada, o erro 404 será executado. O metodo possui somente dois parâmetros.

- int **$code**: Codigo do erro sendo registrado.
- callable **$callback**: Função de callback onde as configurações do erro são definidas.

Por hora a unica configuração disponivel é o **controlador** que segue o mesmo padrão dos controladores de rota.