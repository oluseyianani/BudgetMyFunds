FROM php:7.2-fpm

WORKDIR /application

# Fix debconf warnings upon build
ARG DEBIAN_FRONTEND=noninteractive

# Install selected extensions and other stuff
RUN docker-php-ext-install pdo pdo_mysql \
    && apt-get update \
    && apt-get install zip unzip \
    && pecl install xdebug-2.8.1 \
    && docker-php-ext-enable xdebug

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

EXPOSE 6001

# CMD ["php", "artisan", "websockets:serve"]
