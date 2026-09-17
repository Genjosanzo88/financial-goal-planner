FROM php:8.4-apache

# Extensión que necesitaremos cuando añadamos MySQL.
RUN docker-php-ext-install pdo pdo_mysql

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