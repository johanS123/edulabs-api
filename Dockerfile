# Usa una imagen base de PHP con Apache
FROM php:8.2-apache

# Instala las extensiones necesarias
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo pdo_pgsql pdo_mysql

# Habilita los módulos de Apache necesarios
RUN a2enmod rewrite

# Copia los archivos del proyecto al contenedor
COPY . /var/www/html

# Configura el directorio de trabajo
WORKDIR /var/www/html

# Instala las dependencias de Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install

# Da permisos al directorio
RUN chown -R www-data:www-data /var/www/html

# Exponer el puerto 80 para Apache
EXPOSE 80

# Comando de inicio
CMD ["apache2-foreground"]
