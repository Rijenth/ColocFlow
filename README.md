# ColocFlow
Vue 3 | Vite | Laravel 10 | PostgreSQL | Tailwind

Objectif : Prise en main du framework Vue3 et application dans le cadre d'un projet concret.

## Commandes

Cloner le répo :

- git clone https://github.com/Rijenth/ColocFlow.git

- cd backend

Créer un fichier .env et le remplir avec les données du .env.exemple

- docker-compose up -d --build

- docker-compose exec php composer install

- docker-compose exec php php artisan key:generate

- docker-compose exec php php artisan optimize:clear

- docker-compose exec php php artisan migrate:fresh
