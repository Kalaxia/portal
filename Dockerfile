FROM php:8-fpm-alpine

RUN apk add --update --no-cache --virtual wget zip unzip icu-dev $PHPIZE_DEPS

RUN docker-php-ext-configure intl \
    && docker-php-ext-install pdo pdo_mysql opcache intl \
    && pecl install -o -f apcu && docker-php-ext-enable apcu \
    && echo "date.timezone = Europe/Paris" > /usr/local/etc/php/php.ini

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/bin/ --filename=composer \
    && php -r "unlink('composer-setup.php');"

RUN pecl clear-cache

CMD ["php-fpm"]
