FROM php:8.1.2-fpm-buster

RUN docker-php-ext-install
RUN curl -sS https://getcomposer.org/installer​ | php --install-dir=/usr/local/bin --filename=composer

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .
RUN composer install
