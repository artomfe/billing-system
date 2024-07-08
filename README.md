# Sistema de Cobranças

## Passo a passo para rodar o projeto

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
