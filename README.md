# Valorant Atlas

Valorant Atlas é uma aplicação web em PHP para explorar dados oficiais do universo Valorant com interface unificada, navegação consistente e foco em legibilidade.

## Proposta

O projeto organiza coleções da Valorant-API em uma experiência única, com prioridade para:

- clareza de navegação
- consistência visual entre páginas
- tratamento adequado de erro, vazio e indisponibilidade
- código reutilizável para renderização e consumo da API

## Funcionalidades

- Home com visão geral das coleções principais.
- Biblioteca unificada de coleções com paginação e filtros por tipo.
- Páginas de detalhe para entidades principais (agentes, armas, mapas, modos e companheiros).
- Visualização técnica genérica para coleções sem página dedicada.
- Página de requisitos de hardware em formato objetivo e responsivo.

## Stack

- PHP 8+
- HTML semântico
- CSS com design system próprio
- JavaScript vanilla
- Valorant-API pública: https://valorant-api.com

## Execução local

1. Clone o repositório:

   git clone https://github.com/AndersonCav/Valorant.git

2. Coloque a pasta em um servidor local com PHP (exemplo: XAMPP em htdocs).

3. Inicie o Apache e acesse:

   http://localhost/Valorant

Não é necessário token ou API key para os endpoints públicos atualmente utilizados.

## Estrutura do projeto

- index.php: página inicial
- collections.php: navegação principal das coleções
- specs.php: requisitos de hardware
- agent.php, weapon.php, map.php, mode.php, buddy.php: páginas de detalhe dedicadas
- entity.php: visualização genérica de item
- api/collection.php: endpoint interno para paginação e resposta padronizada
- includes/app.php: configuração das coleções, cliente da API e helpers
- includes/layout.php: head, header e footer compartilhados
- css/app.css: estilos globais
- js/app.js: navegação mobile e carregamento dinâmico da biblioteca
- PHP/*.php: redirecionamentos legados

## Observações

- A aplicação depende da disponibilidade da Valorant-API.
- Campos e mídias podem variar conforme o endpoint.
- Em caso de falha da API, a interface exibe mensagens de erro e mantém navegação funcional.

## Licença e marcas

Projeto independente para estudo e portfólio técnico. Valorant e marcas associadas pertencem à Riot Games.