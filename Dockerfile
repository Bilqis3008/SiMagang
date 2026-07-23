FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    libpq-dev

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd zip

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Update Apache DocumentRoot to point to Laravel's public directory
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Configure Apache to listen on the port specified by Cloud Run's PORT env variable
ENV PORT=8080
RUN sed -s -i -e 's/80/${PORT}/' /etc/apache2/ports.conf /etc/apache2/sites-available/*.conf

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install Node.js (needed for Vite and building frontend assets)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Set working directory
WORKDIR /var/www/html

# Copy the application source code
COPY . /var/www/html

# Copy .env.example as .env if .env doesn't exist during build,
# though passing env vars at runtime is generally better.
RUN if [ ! -f .env ]; then cp .env.example .env; fi

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Install Node dependencies and build assets
RUN npm install
RUN npm run build

# Generate application key (only useful if we copied .env above, but good for self-contained images)
# Note: For production, you should inject APP_KEY via environment variables.
RUN php artisan key:generate || true

# Create the SQLite database and run migrations so the tables exist in the container!
RUN touch database/database.sqlite \
    && php artisan migrate --force \
    && php artisan db:seed --force || true

# Set appropriate permissions for Laravel's cache/storage directories and SQLite database
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/database

# Expose port 80
EXPOSE 8080

# Start Apache in the foreground
CMD ["apache2-foreground"]
