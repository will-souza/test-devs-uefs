#!/bin/sh

# Configura o banco de dados SQLite
touch /var/www/html/database/database.sqlite
chown www-data:www-data /var/www/html/database/database.sqlite
chmod 664 /var/www/html/database/database.sqlite

php artisan migrate --seed

exec "$@"
