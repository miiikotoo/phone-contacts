FROM php:8.2-apache-bookworm
RUN apt update
RUN apt install -y icu-devtools libicu-dev #use intl
RUN apt install -y vim wget git zip unzip
RUN docker-php-ext-install intl
RUN docker-php-ext-install pdo_mysql
RUN a2enmod rewrite
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer