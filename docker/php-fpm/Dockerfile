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

ENTRYPOINT ["/app/docker/php-fpm/entrypoint.sh"]