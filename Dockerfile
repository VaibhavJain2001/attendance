# Use PHP 8.2 FPM
FROM php:8.2-fpm

# Install system dependencies, Nginx, Node.js, npm
RUN apt-get update && apt-get install -y \
    unzip git curl libpq-dev libzip-dev zip nodejs npm nginx \
    && rm -rf /var/lib/apt/lists/*

# Install Composer globally
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Install Node dependencies & build assets
RUN npm ci && npm run build

# Copy entrypoint script
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Copy Nginx config
COPY nginx.conf /etc/nginx/sites-available/default

# Expose HTTP port (Render expects HTTP)
EXPOSE 8080

# Use entrypoint to run DB-dependent commands and start services
CMD ["entrypoint.sh"]
