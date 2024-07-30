FROM php:8.1-fpm-alpine

RUN apk add --no-cache \
    libzip-dev \
    zlib-dev \
    $PHPIZE_DEPS \
    openldap-dev

RUN docker-php-ext-install pdo pdo_mysql sockets ldap

RUN curl -sS https://getcomposer.org/installer | php -- \
     --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /app

# Copy project files
COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction


# Generate key
RUN php artisan key:generate

# Clear cache
RUN php artisan optimize:clear

# Set permissions
RUN chown -R www-data:www-data /app


RUN rm -rf /var/cache/apk/* /tmp/* /var/tmp/*

CMD ["php-fpm"]
