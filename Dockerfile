
FROM php:8.2-apache

RUN apt-get update && apt-get install -y nano \
    && docker-php-ext-install pdo pdo_mysql

RUN sed -i 's|<VirtualHost \*:80>|<VirtualHost *:8123>|g' /etc/apache2/sites-available/000-default.conf \
    && sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|g' /etc/apache2/sites-available/000-default.conf \
    && sed -i 's|#ServerName www.example.com|ServerName localhost|g' /etc/apache2/sites-available/000-default.conf

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . /var/www/html

RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 775 /var/www/html/storage

RUN a2enmod rewrite

RUN echo "Listen 8123" >> /etc/apache2/ports.conf

EXPOSE 8123