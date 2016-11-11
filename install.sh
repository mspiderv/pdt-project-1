sudo chmod -R a-w storage/
sudo chmod -R a-w bootstrap/cache/
cp .env.example .env
composer install
php artisan key:generate
echo "Please configure .env file, then import map data."