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

Acesse em:

http://localhost:8989

## Documentação da API

A API deste projeto está documentada usando o Swagger.

[Link da documentação](http://localhost:8989/api/docs)

## Executando testes

Executando todos os testes do projeto
```sh
php artisan test
```

## Estrutura do Projeto

```
app/
├── Http/
│ ├── Controllers/
│ │ ├── API/
│ │ │ ├── BillingController.php 
│ ├── Requests/
│ │ ├── ProcessFileRequest.php 
├── Imports/
│ ├── BillingImport.php 
├── Jobs/
│ ├── ProcessBill.php 
│ ├── SendMail.php 
├── Models/
│ ├── Billing.php 
│ ├── PaymentSlip.php
├── Services/
│ ├── BillingService.php
...
tests/
├── Feature/
│ ├── BillingControllerTest.php
│ ├── BillingServiceTest.php
├── Unit/
│ ├── BillingControllerTest.php
│ ├── BillingServiceTest.php
│ ├── ProcessBillJobTest.php
│ ├── SendMailJobTest.php
```

### Controllers

- **API**
  - `BillingController.php`: Controlador da API para gerenciamento de cobranças

### Requests

- `ProcessFileRequest.php`: Requisição para validar os dados recebidos na Request

### Diretório `Imports/`

- `BillingImport.php`: Arquivo com a lógica de importação dos dados da planilha

### Diretório `Jobs/`

- `ProcessBill.php`: Job para processamento do documento de cobrança
- `SendMail.php`: Job para envio de e-mails

### Diretório `Models/`

- `Billing.php`: Modelo de dados para cobranças
- `PaymentSlip.php`: Modelo de dados para os boletos de pagamento

### Diretório `Services/`

- `BillingService.php`: Serviço para lógica de negócios relacionada as cobranças

### Tests
- **Feature**
  - `BillingControllerTest.php`: Testes de integração para o BillingController
  - `BillingServiceTest.php`: Testes de integração para o BillingService

- **Unit**
  - `BillingControllerTest.php`: Testes unitários para o BillingController
  - `BillingServiceTest.php`: Testes unitários para o BillingService
  - `ProcessBillJobTest.php`: Testes unitários para o Job de Processamento de boletos
  - `SendMailJobTest.php`: Testes unitários para o Job de Envio de E-mails





