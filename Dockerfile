# Use official PHP image with Apache
FROM php:8.2-apache

# Enable OpenSSL extension (required for encryption)
RUN docker-php-ext-install openssl

# Enable mod_rewrite (optional if you use URL rewriting)
RUN a2enmod rewrite

# Copy all project files into container's web root
COPY . /var/www/html/

# Set working directory
WORKDIR /var/www/html/

# Set permissions for uploads folder (make writable)
RUN chown -R www-data:www-data /var/www/html/uploads \
    && chmod -R 755 /var/www/html/uploads

# Expose port 80
EXPOSE 80

# Start Apache in foreground
CMD ["apache2-foreground"]
