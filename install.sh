sudo chmod -R 777 storage/
sudo chmod -R 777 bootstrap/cache/
cp .env.example .env
sudo chmod 777 .env
composer install
php artisan key:generate
echo "Please configure .env file, then import map data."