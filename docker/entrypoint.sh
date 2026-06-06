#!/bin/sh
set -e

mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs
mkdir -p bootstrap/cache

chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

php artisan package:discover --ansi || true

php artisan config:clear || true
php artisan cache:clear || true
php artisan view:clear || true

php artisan storage:link || true

php artisan migrate --force

if [ "$RUN_SEEDER" = "true" ]; then
    php artisan db:seed --force
fi

php artisan config:cache
php artisan view:cache || true

exec "$@"