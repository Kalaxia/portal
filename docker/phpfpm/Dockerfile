FROM php:fpm
MAINTAINER kern <kern046@gmail.com>

RUN apt-get update \
    && apt-get upgrade -y \
    && apt-get install -y wget zip unzip \

    && docker-php-ext-install pdo pdo_mysql \

    && wget https://phar.phpunit.de/phpunit-6.1.phar \
    && chmod +x phpunit-6.1.phar \
    && mv phpunit-6.1.phar /usr/bin/phpunit \

    && php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/bin/ --filename=composer \
    && php -r "unlink('composer-setup.php');"