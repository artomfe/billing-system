# Sistema de Cobranças

## Descrição

Este projeto foi desenvolvido utilizando Laravel 11 e visa facilitar o gerenciamento de cobranças, processamento de boletos e envio de e-mails automatizados.

## Instalação do Projeto - Passo a passo

Clone o Repositório
```sh
git clone https://github.com/artomfe/billing-system.git
```

Crie o Arquivo .env 
```sh
cp .env.example .env
```

Crie os containers do projeto
```sh
docker-compose up -d --build
```

Instale as dependências do projeto
```sh
docker-compose exec app composer install
```

Gere a key do projeto Laravel
```sh
docker-compose exec app php artisan key:generate
```

Rode as migrations do projeto
```sh
docker-compose exec app php artisan migrate
```

Rode o swagger
```sh
docker-compose exec app php artisan l5-swagger:generate
```

Acesse em

http://localhost:8989

## Estrutura do Projeto

## Documentação da API

A API deste projeto está documentada usando o Swagger.

[Link da documentação](http://localhost:8989/api/docs)

## Executando testes

Executando todos os testes do projeto
```sh
php artisan test
```
