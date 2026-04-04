# Valorant Atlas

## Visão Geral

Valorant Atlas é uma aplicação PHP orientada à exploração de dados da API pública do Valorant.
O projeto foi estruturado com Front Controller e padrão MVC para separar responsabilidades de roteamento, lógica de negócio, integração HTTP e renderização de views.

## Stack Tecnológica

- PHP 8.4
- Docker e Docker Compose
- Composer para gestão de dependências
- Autoload PSR-4 (namespace `App\\`)
- Padronização de código com PSR-12
- Guzzle (`guzzlehttp/guzzle`) para integração HTTP
- PHPUnit para testes automatizados
- GitHub Actions para integração contínua

## Como Executar

1. Suba o ambiente local:

   ```bash
   docker-compose up -d
   ```

2. Instale as dependências do projeto:

   ```bash
   composer install
   ```

3. Acesse a aplicação no navegador:

   ```text
   http://localhost
   ```

## Estrutura do Projeto

```text
.
├── index.php
├── src/
│   ├── Controllers/
│   ├── Core/
│   ├── Services/
│   └── Views/
├── tests/
│   └── Unit/
├── .github/
│   └── workflows/
├── Dockerfile
├── docker-compose.yml
├── composer.json
└── phpunit.xml
```

## Diferenciais

- Arquitetura MVC com Front Controller para centralização de rotas e fluxo HTTP.
- Base preparada para desenvolvimento local e deploy em ambientes conteinerizados.
- Testes unitários com PHPUnit para componentes críticos.
- Pipeline de CI em GitHub Actions com validação de estilo (PHP CS Fixer em PSR-12) e execução de testes.