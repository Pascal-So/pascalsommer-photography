FROM php:8.2-apache

# Install dependencies

RUN apt-get update && apt-get install -y \
        libfreetype-dev \
        libjpeg-dev \
        libwebp-dev \
        libpng-dev \
        git \
        unzip \
    && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-configure pdo_mysql \
    && docker-php-ext-install -j$(nproc) pdo_mysql

SHELL ["/bin/bash", "-c"]
RUN ln -s ../mods-available/{expires,headers,rewrite}.load /etc/apache2/mods-enabled/
RUN sed -e '/<Directory \/var\/www\/>/,/<\/Directory>/s/AllowOverride None/AllowOverride All/' -i /etc/apache2/apache2.conf


# Install composer

RUN curl -sS https://getcomposer.org/installer -o /tmp/composer-setup.php
RUN php /tmp/composer-setup.php --install-dir=/usr/local/bin --filename=composer


# Install the Laravel PHP application

WORKDIR /var/www/html/laravel

COPY laravel/composer.json laravel/composer.lock laravel/artisan ./
COPY laravel/bootstrap/ ./bootstrap/
COPY laravel/app/ ./app/
COPY laravel/config/ ./config/
COPY laravel/routes/ ./routes/
COPY laravel/resources/ ./resources/
COPY laravel/database/ ./database/
RUN mkdir -p storage/{app,framework/{cache,sessions,views},logs}

RUN composer install


# Add static files to the web root

WORKDIR /var/www/html

COPY .htaccess index.php mix-manifest.json robots.txt .
COPY css/ ./css
COPY fonts/ ./fonts
COPY img/pascalsommerphotography.jpg ./img/pascalsommerphotography.jpg
COPY js/ ./js


# Switch to www-data user

RUN chown -R www-data:www-data $PWD
USER www-data
