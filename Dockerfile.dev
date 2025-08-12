FROM php:8.2-apache
# Instalar extensiones necesarias para mysqli
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli
WORKDIR /var/www/html
COPY ./src/ .
# Dar permisos (opcional, solo si necesitas escritura)
RUN chown -R www-data:www-data /var/www/html
EXPOSE 80
