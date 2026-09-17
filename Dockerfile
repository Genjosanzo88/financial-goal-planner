FROM php:8.4-apache
RUN a2enmod rewrite

# Herramientas y extensiones necesarias para Composer y MySQL.
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        libzip-dev \
        unzip \
    && docker-php-ext-install \
        pdo \
        pdo_mysql \
        zip \
    && rm -rf /var/lib/apt/lists/*

# Composer dentro del contenedor.
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Apache servirá la carpeta /public.
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN sed -ri \
    -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf \
    /etc/apache2/apache2.conf \
    /etc/apache2/conf-available/*.conf

WORKDIR /var/www/html