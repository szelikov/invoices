FROM php:8.5.9-fpm-alpine3.23

RUN apk add --no-cache postgresql-dev
RUN docker-php-ext-install pdo pdo_pgsql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

ARG WORKDIR=/app
WORKDIR ${WORKDIR}

ARG PORT
EXPOSE $PORT
