# Use official PHP 8.2 FPM image
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

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Install Node.js dependencies
RUN npm install

# Fix permissions (Laravel)
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Expose Laravel FPM and Vite ports
EXPOSE 9000 5173

# Run PHP-FPM and Vite dev server in the same container
CMD ["sh", "-c", "php-fpm & npm run dev"]
