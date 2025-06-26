FROM php:8.3-fpm

# Install Node.js and npm
RUN apt-get update && apt-get install -y curl gnupg \
  && curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
  && apt-get install -y nodejs

# Set working directory
WORKDIR /var/www

# Copy package files first to leverage Docker cache
COPY package*.json ./

# Install Node.js dependencies (including Vite)
RUN npm install

# Copy rest of application
COPY . .

# Install PHP dependencies
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
RUN composer install

# Build assets
RUN npm run build

# Fix permissions
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Expose ports
EXPOSE 1006

# Start the server
CMD ["sh", "-c", "composer run dev"]


