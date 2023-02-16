dazd# ColocFlow
Reworked ColocApp using Vue3 |

git clone https://github.com/Rijenth/ColocFlow.git

cd backend && composer install

docker-compose up -d

docker-compose exec php php artisan key:generate

docker-compose exec php php artisan optimize:clear
