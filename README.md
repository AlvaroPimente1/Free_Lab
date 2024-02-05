<h1 align="center">NMTSisLab</h1>

## Sobre
O NMTSisLab é um sistema que objetiva gerenciar laudos, separando os dados em pacientes e laboratórios. Para ele, utiliza-se a princípio a arquitetura pré-pronta do Laravel, e o Bootstrap serve como uma biblioteca de estilização para o front end. 

O sistema foi construído ao longo de quase 1 ano e meio, então existem pedaços dele que demonstram o amadorismo dos dois programadores iniciais. (@ilvinipitter e @viclopes) Porém, a maior parte foi refatorada mais à frente, então o código é razoavelmente robusto.

O Laravel é um framework com ampla [documentação](https://laravel.com/docs) que deve ser o primeiro passo de qualquer um que lide com este sistema. É importantíssimo entender como ele lida com as relações entre seus documentos.

## O que eu devo instalar na máquina para usar?
* PHP > 7.3 & Composer
* Laravel 7.x
* MariaDB > 10.x
* Git > 2.x

### É recomendado também
#### Extensões do VSCode
* Git Blame
* Colorize
* Auto Close Tag
* Bracket Pair Colorizer
* Git History
* PHP Intelephense
* Prettier - Code Formatter

## Como funciona?
Clone o repositório, e rode `composer install`.

São diversas operações CRUD realizadas em alguns modelos. Esses modelos são:
* Laboratory
* Patient
* Procedure
* Report
* User

Eles (e seus controladores) podem ser vistos na pasta `/app/`, além de suas relações. 
Por exemplo, em `Laboratory.php` temos:
```
public function administrator()
{
    return $this->belongsToMany(User::class, 'administrator_laboratory');
}
```

E dentro do php artisan tinker, podemos realizar a chamada `$laboratory->administrator` e isso deverá retornar a Coleção do Eloquent que exibe todos os administradores do laboratório (Ou seja, um JSON).

Todos os modelos, a princípio, possuem as operações do CRUD por padrão. Algumas foram removidas por design, como a capacidade de deletar laudos (Report) ou laboratórios (Laboratory).

Todas as rotas devem estar presentes em `/routes/web.php`.

Os scripts de javascript utilizados estão presentes em `/public/js/`, assim como as stylesheets estão em `/public/css`.

Todos os views puxam o design base de `/resources/views/layouts/app.blade.php`, então qualquer mudança a ser realizada em todas as páginas **deve** ser feita ali. Do contrário, todo o conteúdo que deveria entrar no body pode ser feito na seção *content* (`@section('content')` e todo o conteúdo da head deve ser feito na seção *headJS* `@yield('headJS')`.

## Os Laudos
Os laudos são organizados como uma string para o banco de dados, feito pelo script presente em diversos pontos. Em `newfield.js` tem-se o script que roda na página de criar/editar modelos, que permite a adição de novos campos. Já em `views/reprots/create.blade.php` tem um script que roda para unir todos os campos presentes àqueles enviados pelo back-end.

Dessa forma, ele utiliza separadores que são uma sequência bem improvável de caracteres para separar os campos e valores.
| Campo 1 | Valor Campo 1 | Valor de Referência Campo 1 |
|---------|---------------|-----------------------------|
| Campo 2 | Valor Campo 2 | Valor de Referência Campo 2 |

Será enviado para o back-end como:
`Campo 1!-!Valor de Referência Campo 1!-!Valor Campo 1@-@Campo 2!-!Valor de Referência Campo 2!-!Valor Campo 2`
Para os modelos, é a mesma coisa, mas o Valor Campo não existe, somente o Campo e seu Valor de Referência. Todos podem ser acessados através de um `explode('@-@')` para separar os campos, seguido de `explode('!-!')`.
