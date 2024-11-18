# Usa una imagen base de PHP con Apache
FROM php:8.2.12-apache

# Instala dependencias del sistema y extensiones de PHP
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip \
    && docker-php-ext-install pdo pdo_mysql

# Habilita el módulo de reescritura de Apache
RUN a2enmod rewrite

# Copia la configuración personalizada de Apache
COPY apache-config.conf /etc/apache2/sites-available/000-default.conf

# Copia los archivos del proyecto al contenedor
COPY . /var/www/html

# Establece el directorio de trabajo
WORKDIR /var/www/edulabs-api

# Ajusta permisos
RUN chown -R www-data:www-data /var/www/edulabs-api \
    && chmod -R 775 /var/www/edulabs-api

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Instala dependencias de Composer
RUN composer install --no-dev --optimize-autoloader

# Exponer el puerto 80
EXPOSE 80
