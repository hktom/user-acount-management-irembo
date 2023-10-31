FROM php:8.2.1-fpm

RUN apt-get clean && apt-get update && apt-get install -y  \
    libmagickwand-dev \
    --no-install-recommends \
    && pecl install imagick \
    && docker-php-ext-enable imagick \
    && docker-php-ext-install pdo_mysql

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# # Set working directory
WORKDIR /var/www/html

# # Copy Laravel files
COPY . .

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache