FROM php:8.2-apache

RUN a2enmod rewrite

# Copy custom php.ini config
COPY php.ini /usr/local/etc/php/

COPY . /var/www/html/

WORKDIR /var/www/html/

RUN mkdir -p uploads && chown -R www-data:www-data uploads && chmod -R 755 uploads

EXPOSE 80

CMD ["apache2-foreground"]
