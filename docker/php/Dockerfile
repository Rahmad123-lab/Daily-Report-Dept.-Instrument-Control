
FROM php:8.3-cli

# System dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    zip \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libzip-dev \
    libicu-dev \
    libonig-dev \
    libxml2-dev \
    libcurl4-openssl-dev \
    ca-certificates \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        bcmath \
        exif \
        pcntl \
        intl \
        mbstring \
        zip \
        gd \
        curl \
        dom \
        opcache \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install Node.js 22
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copy Laravel application
COPY src/ ./

# Copy TiDB Cloud CA certificate
COPY docker/certs/tidb-ca.pem /etc/ssl/certs/tidb-ca.pem

# Install PHP dependencies
RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader

# Build frontend assets
RUN npm ci \
    && npm run build \
    && rm -rf node_modules

# Prepare Laravel directories
RUN mkdir -p \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Railway application port
EXPOSE 8080

# Start Laravel
CMD ["sh", "-c", "php artisan storage:link || true; php artisan serve --host=0.0.0.0 --port=${PORT:-8080}"]
