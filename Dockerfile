FROM php:8.0-fpm

RUN apt-get update; \
    apt-get install -y --no-install-recommends \
    coreutils \
    make \
    iproute2 \
    curl \
    libcurl4-openssl-dev \
    libxml2-dev \
    zlib1g-dev \
    libpng-dev \
    libpq-dev \
    libssl-dev \
    libzip-dev \
    libxslt1-dev \
    bash \
    zip \
    git \
    unzip


RUN docker-php-ext-install \
    gd \
    pdo \
    intl \
    opcache \
    pdo_mysql \
    phar \
    tokenizer \
    zip \
    xsl

#setup composer
RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/ \
    && ln -s /usr/local/bin/composer.phar /usr/local/bin/composer

WORKDIR /var/www/html
COPY . ./
RUN composer install
