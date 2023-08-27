## Установка и настройка проект

- `docker-compose build`
- `docker-compose up -d`
- `docker-compose exec php-fpm bash`
- `su apps`
- `composer install`
- `php -r "file_exists('.env') || copy('.env.example', '.env');"`
- `php artisan migrate --seed`
- `php artisan key:generate`
- `php artisan passport:install`
- `php artisan co:ca`
- `chmod -R 775 storage bootstrap/cache`

В hosts файл хоста добавить: `127.0.0.1 api.div-request.local`.
В винде данный файл находится в: `C:\Windows\System32\drivers\etc\hosts`.
В линуксе: `/etc/hosts`.

Подключение к БД из idea: `127.0.0.1:3307`
## Документация
- [Swagger](http://api.div-request.local/api/documentation)
