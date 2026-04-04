FROM php:8.4-apache

# Dependencias de sistema e extensoes PHP comuns para APIs e serializacao
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        git \
        unzip \
        libicu-dev \
        libzip-dev \
    && docker-php-ext-install \
        curl \
        intl \
        mbstring \
        opcache \
        zip \
    && a2enmod rewrite headers \
    && rm -rf /var/lib/apt/lists/*

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN sed -ri -e "s!/var/www/html!${APACHE_DOCUMENT_ROOT}!g" /etc/apache2/sites-available/*.conf /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Instala Composer oficial
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copia metadados primeiro para otimizar cache de build
COPY composer.json ./
RUN composer install --no-interaction --no-progress --prefer-dist --optimize-autoloader --no-dev

# Copia aplicacao
COPY . .

# Garante permissao basica para runtime do Apache
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80

HEALTHCHECK --interval=30s --timeout=5s --start-period=20s --retries=3 \
  CMD curl -f http://localhost/ || exit 1
