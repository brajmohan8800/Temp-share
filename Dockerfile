FROM php:8.2-apache

RUN a2enmod rewrite

COPY . /var/www/html/

WORKDIR /var/www/html/

# Pehle folder create karo, phir permissions set karo
RUN mkdir -p /var/www/html/uploads \
    && chown -R www-data:www-data /var/www/html/uploads \
    && chmod -R 755 /var/www/html/uploads

EXPOSE 80

CMD ["apache2-foreground"]
