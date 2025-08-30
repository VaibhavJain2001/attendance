#!/bin/sh

# Wait for DB to be ready (optional: Render DB may already be ready)
# You can use a loop to check DB availability if needed
# Example:
# while ! nc -z $DB_HOST $DB_PORT; do
#   echo "Waiting for database..."
#   sleep 2
# done

# Cache Laravel configs/routes/views at runtime
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start PHP-FPM
php-fpm
