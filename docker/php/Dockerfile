FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpq-dev \
    libzip-dev \
    libicu-dev \
    zip \
    unzip \
    sudo \
    && docker-php-ext-install pdo_pgsql zip intl \
    && pecl install redis \
    && docker-php-ext-enable redis intl

ARG UID=1000
ARG GID=1000

RUN groupadd -g ${GID} mars && \
    useradd -u ${UID} -g ${GID} -m mars && \
    usermod -aG sudo mars && \
    echo 'mars:mars' | chpasswd

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

USER mars

WORKDIR /var/www