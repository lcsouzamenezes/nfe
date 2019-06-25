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

FROM php:7.2.5-fpm

RUN apt-get update

RUN apt-get install -y git

RUN echo 'depencias do mongo'

RUN apt-get install -y libcurl4-openssl-dev pkg-config libssl-dev

RUN docker-php-ext-install mbstring pdo

RUN pecl install mongodb

RUN docker-php-ext-enable mongodb

RUN apt-get install -y libxml2-dev

RUN docker-php-ext-install soap

WORKDIR /app

COPY . /app

COPY --from=vendor /app/vendor/ /app/vendor/

RUN chmod -R 777 /app/storage

ENTRYPOINT ["/app/docker/php-fpm/entrypoint.sh"]
