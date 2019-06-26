# NF-E 

Este projeto tem como objetivo: 

* Comunicação com a API da [SEFAZ](http://www.nfe.fazenda.gov.br)
    * Distribuição do DFe.
    * Geração de NF-e modelo 55.
    * Consulta do estado das notas junto Sefaz.
    * Cancelamento de NF-e.
* Facilitar a comunicação com API SOAP, abstraindo o formato para JSON.
* Recuperar notas contra CNPJ/CPF.
* Armazenar de forma segura as NFe pelo periodo exigido de 5 anos.
* Gerenciar certificados de varios CNPJs e CPF.

<a href="https://app.zenhub.com/workspaces/nfe-5c86a3bf6cd59109e9e64e82/boards?repos=171279463" target="_blank">
    <img src="https://img.shields.io/badge/Managed_with-ZenHub-5e60ba.svg" alt="zenhub">
</a>

[![Build Status](https://travis-ci.com/culturagovbr/nfe.svg?branch=master)](https://travis-ci.com/culturagovbr/nfe)


## O que nos temos?

* Criação das NF-e 
* Retorna os dados da NF-e
* Consultar Recibo Nota Fiscal
* Cadastro dos Certificados
* Certificado Listar
* Autenticação por Token JWT

## Gerenciamento e Organização
Estamos usando o [ZenHub](https://app.zenhub.com/workspaces/nfe-5c86a3bf6cd59109e9e64e82/boards?repos=171279463) para organizar e priorizar as issues.

## Instalação
Para criar o ambiente de desenvolvimento trabalhar com o NFE basta executar os passos:

1. Criar o seu docker-compose.yaml:
```
cp docker-compose.yml-sample docker-compose.yml
```
2. Subir os containers:
```
docker-compose up -d
```
Para parar os containers basta digitar:
```
docker-compose down
```
## Quem usa?

* [Salic](http://salic.cultura.gov.br/) - Sistema de Apoio e Incentivo a Leis de Cultura.


## Tecnologias:
* [Docker](https://www.docker.com/)
* [PHP 7.x](http://php.net/)
* [Lumen 5.8](https://lumen.laravel.com/) 
* [Composer](https://getcomposer.org/)
* [Sentry](https://sentry.io/welcome/)
* [NfePHP](https://github.com/nfephp-org/)
* [Travis](https://travis-ci.com/culturagovbr/nfe)
* [Min.io](https://www.min.io/)
* [Mongo](https://www.mongodb.com/)
* [API Blueprint](https://apiblueprint.org/)

Link uteis:
[Consulta NFE Simplificada](http://www.nfe.fazenda.gov.br/portal/consultaRecaptcha.aspx?tipoConsulta=resumo&tipoConteudo=d09fwabTnLk=)

Tutoriais:
[Emitindo Nfe com PHP](https://imasters.com.br/back-end/emitindo-nfe-com-php)
