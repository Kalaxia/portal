FROM php:7.4-fpm

RUN apt-get update \
    && apt-get upgrade -y \
    && apt-get install -y wget zip unzip libicu-dev \
    && usermod -u 1000 www-data \
    && docker-php-ext-configure intl \
    && docker-php-ext-install pdo pdo_mysql opcache intl \
    && wget https://phar.phpunit.de/phpunit-9.phar \
    && chmod +x phpunit-9.phar \
    && mv phpunit-9.phar /usr/bin/phpunit \
    && echo "date.timezone = Europe/Paris" > /usr/local/etc/php/php.ini \
    && php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/bin/ --filename=composer \
    && php -r "unlink('composer-setup.php');"

CMD ["php-fpm"]
