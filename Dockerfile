# usa una imagen base de php con Apache
FROM php:8.1-apache

# Instala las extensiones necesarias para Slim y Composer
RUN docker-php-ext-install pdo pdo_mysql

# Habilita el modulo de reescritura de Apache
RUN a2enmod rewrite

# Copia los archivos del proyecto al contenedor
COPY . /var/www/html

# Establece el directorio de trabajo
WORKDIR /var/www/html

# Configura permisos para Apache
RUN chown -R www-data:www-data /var/www/html \&& chmod -R 755 /var/www/html

# Instala composer
COPY --from=composer:lastest /usr/bin/composer /usr/bin/composer

# Instala las dependencias de composer
Run composer install --no-dev --optimize-autoloader

# Exponer el puerto
EXPOSE 80