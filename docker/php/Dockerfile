FROM php:7.3-fpm-alpine

RUN apk upgrade --update && apk add --no-cache \
    $PHPIZE_DEPS \
    git \
    wget \
    vim \
    supervisor \
    icu-dev \
    libpng-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    openssl-dev \
    pkgconfig \
    unixodbc-dev \
    zlib-dev libzip-dev \
    rabbitmq-c-dev \
    imagemagick-dev \
    imagemagick \
    tzdata

RUN cp /usr/share/zoneinfo/Europe/Moscow /etc/localtime && "date"

RUN docker-php-ext-configure zip --with-libzip \
    && docker-php-ext-install -j$(nproc) iconv pdo mysqli pdo_mysql opcache mbstring zip pcntl \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ --with-png-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl

RUN pecl install apcu \
    && docker-php-ext-enable apcu

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer --version

RUN pecl install amqp \
    && docker-php-ext-enable amqp \
    && docker-php-ext-install bcmath

RUN mkdir /app

WORKDIR /app