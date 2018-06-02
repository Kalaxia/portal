FROM php:fpm
MAINTAINER kern <kern046@gmail.com>

COPY docker-entrypoint.sh /entrypoint.sh
COPY crontab /etc/cron.d/app-cron

RUN chown root:root /entrypoint.sh && chmod a+x /entrypoint.sh \
    && apt-get update \
    && apt-get upgrade -y \
    && apt-get install -y cron wget zip unzip dos2unix \
    
    && dos2unix /entrypoint.sh \

    && usermod -u 1000 www-data \

    && chmod 0644 /etc/cron.d/app-cron \
    && touch /var/log/cron.log \

    && docker-php-ext-install pdo pdo_mysql opcache \

    && wget https://phar.phpunit.de/phpunit-6.1.phar \
    && chmod +x phpunit-6.1.phar \
    && mv phpunit-6.1.phar /usr/bin/phpunit \

    && echo "date.timezone = Europe/Paris" > /usr/local/etc/php/php.ini \

    && php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/bin/ --filename=composer \
    && php -r "unlink('composer-setup.php');"

ENTRYPOINT ["/entrypoint.sh"]

CMD ["php-fpm"]
