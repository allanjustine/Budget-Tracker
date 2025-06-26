# Use official PHP 8.3 FPM image
FROM php:8.3-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    git unzip libpng-dev libjpeg-dev libfreetype6-dev libonig-dev libzip-dev zip curl \
    nodejs npm

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip

# Set working directory
WORKDIR /var/www

# Copy application source
COPY . .

# Set PATH to include local node_modules/.bin
ENV PATH="/var/www/node_modules/.bin:$PATH"

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Install Node.js dependencies
RUN npm install

# Fix permissions (Laravel)
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Expose ports
EXPOSE 1006

# Run PHP-FPM and Vite dev server
CMD ["sh", "-c", "php-fpm & npm run dev"]
