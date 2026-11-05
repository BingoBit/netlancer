FROM php:8.2-apache

RUN docker-php-ext-install pdo pdo_mysql \
    && a2enmod rewrite headers \
    && sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

WORKDIR /var/www/html

COPY . /var/www/html

RUN cp config.example.php config.php \
    && mkdir -p assets/uploads/resumes \
    && chmod -R 775 assets/uploads \
    && chown -R www-data:www-data /var/www/html

EXPOSE 80
