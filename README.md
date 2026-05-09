# ICA Financeiro

Sistema financeiro do Instituto Carioca de Anestesiologia. Aplicação PHP legada (originalmente PHP 5.x), adaptada para rodar em PHP 8.4 + Apache + MySQL.

## Stack

- **PHP 8.4** com `mysqli` e OPcache
- **Apache** (mod_php via container `php:8.4-apache`)
- **MySQL** remoto (credenciais não versionadas — ver "Configuração")
- **Docker / Docker Compose** para ambiente de desenvolvimento

## Como rodar localmente

```bash
docker compose up -d --build
```

Acesse `http://localhost:8080/login.php`.

Para acompanhar logs:

```bash
docker compose logs -f web
docker compose exec web tail -f /var/log/php_errors.log
```

## Configuração

As credenciais do banco ficam em `financeiro/classes/conexao.class.php` e foram removidas deste repositório por segurança. Antes de rodar, edite o arquivo preenchendo:

```php
$this->host     = "p:HOST_DO_MYSQL";  // o prefixo p: ativa conexão persistente
$this->user     = "USUARIO";
$this->pass     = "SENHA";
$this->database = "NOME_DO_BANCO";
```

## Estrutura

```
.
├── Dockerfile              # Imagem PHP 8.4 + Apache + mysqli + opcache
├── docker-compose.yml      # Sobe o app na porta 8080
└── financeiro/             # Aplicação PHP (raiz do docroot Apache)
    ├── classes/            # Classes de domínio (cadastro, login, relatorios, etc.)
    │   └── autoload.php    # spl_autoload_register central
    ├── paginas/            # Páginas roteadas via index.php?s=<nome>
    ├── modal/              # Modais reutilizáveis
    ├── script/             # Endpoints AJAX
    ├── estilos/            # CSS, JS, datepicker
    ├── jquery/, jquery2/   # Bibliotecas jQuery (versões legadas mantidas)
    ├── index.php           # Dashboard (router)
    ├── login.php           # Tela e POST de login
    └── search.php          # Endpoint AJAX de autocomplete
```

## Histórico de migração

Adaptado de PHP 5.x → PHP 8.4 com:

- `__autoload` substituído por `spl_autoload_register`
- Prepared statements em queries que recebem input do usuário
- Conexões persistentes (`p:` prefix) e `set_charset()` em vez de `SET NAMES`
- Sessão com `use_strict_mode`, cookies `HttpOnly` e `SameSite=Lax`
- OPcache habilitado
