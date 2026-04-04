# Valorant Atlas

Valorant Atlas e uma aplicacao web em PHP focada em exploracao de dados do universo Valorant com curadoria visual, navegacao consistente e consumo robusto da API publica Valorant-API.

O projeto foi reestruturado para sair do modelo de paginas duplicadas por endpoint e evoluir para uma arquitetura unificada, com componentes compartilhados, tratamento de erro e padrao visual unico.

## Proposta

Entregar uma experiencia de portfolio com foco em qualidade de interface e sustentabilidade do codigo, priorizando:

- exploracao das colecoes mais importantes (agentes, armas, mapas e modos)
- navegacao coesa entre home, colecoes e requisitos
- feedback claro para falha de rede, resposta invalida e estado vazio
- organizacao modular para evitar repeticao de layout e logica

## Funcionalidades principais

- Home page orientada a produto, com destaque para colecoes principais e panorama em tempo real.
- Biblioteca unificada de colecoes com carregamento dinamico via endpoint interno.
- Filtros por tipo de colecao com paginacao, estado de carregamento e estado de erro.
- Requisitos tecnicos organizados em layout semantico e responsivo.
- Compatibilidade com links legados das paginas antigas, via redirecionamento para a nova interface.

## Stack

- PHP 8+ (renderizacao server-side e endpoint interno)
- HTML semantico
- CSS customizado com design system centralizado
- JavaScript vanilla para interacao da biblioteca de colecoes
- Valorant-API publica (https://valorant-api.com)

## Como executar localmente

1. Clone o repositorio:

   git clone https://github.com/AndersonCav/Valorant.git

2. Coloque a pasta do projeto em um servidor local com PHP (por exemplo, XAMPP em htdocs).

3. Inicie Apache e acesse:

   http://localhost/Valorant

Nao e necessario token ou API key para usar os endpoints publicos atuais da Valorant-API.

## Estrutura do projeto

- index.php: home da aplicacao
- collections.php: experiencia principal de exploracao de dados
- specs.php: pagina de requisitos tecnicos
- api/collection.php: endpoint interno para normalizar resposta de colecoes
- includes/app.php: configuracao de colecoes, cliente HTTP e helpers
- includes/layout.php: layout compartilhado (head, header e footer)
- css/app.css: design system global
- js/app.js: navegacao mobile e consumo dinamico da biblioteca
- PHP/*.php: redirecionamentos legados para preservar rotas antigas

## Decisoes de arquitetura

- Consolidacao de paginas repetidas em uma unica experiencia de colecoes.
- Eliminacao de CSS inline e de estilos duplicados.
- Remocao de recursos que prejudicavam UX, como autoplay de audio embutido.
- Tratamento explicito de falhas de cURL, status HTTP invalido e payload inconsistente.
- Acesso seguro a campos aninhados da API para reduzir erros por dados nulos.

## Limitacoes e observacoes

- A aplicacao depende da disponibilidade da Valorant-API publica.
- Parte do conteudo visual (imagens) pode variar de qualidade conforme o endpoint.
- Em caso de indisponibilidade da API, a interface exibe feedback de erro, mas nao usa cache local.

## Licenca e marcas

Este projeto e independente e educacional. Valorant e marcas associadas pertencem a Riot Games.