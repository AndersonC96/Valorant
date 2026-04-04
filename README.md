# Valorant Atlas

## Visão Geral

Valorant Atlas é uma aplicação PHP para consumo e exploração da API pública do Valorant. A base do projeto foi organizada com arquitetura MVC e Front Controller para separar o fluxo HTTP, a lógica de domínio, a integração com a API e a renderização de views de forma explícita e testável.

## Stack Tecnológica

- PHP 8.4
- Docker e Docker Compose para execução local isolada
- Composer para gestão de dependências e autoload PSR-4
- Padronização de código segundo PSR-12
- Guzzle para comunicação HTTP com a API externa
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

3. Acesse a aplicação pelo navegador no endereço configurado pelo ambiente Docker.

## Estrutura do Projeto

O ponto de entrada da aplicação é o `index.php` na raiz do repositório.

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
├── docker-compose.yml
├── Dockerfile
├── composer.json
└── phpunit.xml
```

## Diferenciais

- Arquitetura MVC com Front Controller para centralizar entrada, roteamento e composição de respostas.
- Dependências geridas por Composer, com autoload PSR-4 e execução consistente entre ambientes.
- Cobertura automatizada com PHPUnit para componentes críticos da aplicação.
- Integração contínua configurada com GitHub Actions para validação de qualidade e execução da suíte de testes.