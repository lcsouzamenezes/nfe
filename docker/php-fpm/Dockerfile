FROM composer:1.7 as vendor

WORKDIR /app

COPY . /app
RUN ls -la

RUN composer install \
    --ignore-platform-reqs \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --prefer-dist

FROM nginx:mainline-alpine

# COPY docker/nginx/nginx.conf /etc/nginx/conf.d/default.conf

COPY docker/nginx/ /etc/nginx/conf.d/

# RUN ls -la /etc/nginx/conf.d/

COPY . /var/www/html

FROM php:7.2.5-fpm

WORKDIR "/app"

RUN apt-get update

RUN apt-get install -y git

RUN echo 'depencias do mongo'

RUN apt-get install -y libcurl4-openssl-dev pkg-config libssl-dev

RUN docker-php-ext-install mbstring pdo

RUN pecl install mongodb

RUN docker-php-ext-enable mongodb

RUN apt-get install -y libxml2-dev

RUN docker-php-ext-install soap

RUN ls -la

COPY . /app

COPY --from=vendor /app/vendor/ /app/vendor/

ENTRYPOINT ["/app/docker/php-fpm/entrypoint.sh"]