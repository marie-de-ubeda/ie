FROM php:7.4-fpm

# Get argument defined in docker-compose file
ARG user
ARG uid

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libpng-dev \
    libxml2-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libmcrypt-dev \
    libpng-dev \
    zlib1g-dev \
    libxml2-dev \
    libzip-dev \
    libonig-dev \
    libmagickwand-dev \
    graphviz \
    unzip \
    bzip2 \
    libbz2-dev -y\
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install pcntl \
    && docker-php-ext-install bcmath \
    && docker-php-ext-install gd \
    && docker-php-ext-install gd \
    && docker-php-ext-install bz2 \
    && docker-php-ext-install json \
    && docker-php-ext-install intl \
    && docker-php-ext-install xml \
    && docker-php-ext-install opcache \
    && docker-php-ext-install soap \
    && docker-php-source delete

RUN apt-get update && apt-get install -y libzip-dev libpng-dev && docker-php-ext-install zip gd


# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Get latest Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer --version=1.10.20


# Set working directory
WORKDIR /var/www

USER $user