# Usa una imagen oficial de PHP con Apache
FROM php:8.2-apache

# Instala extensiones necesarias (como PDO si usas bases de datos)
RUN docker-php-ext-install pdo pdo_mysql

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Instalar dependencias
RUN composer install

# Copia los archivos del proyecto al contenedor
COPY . /var/www/html/

# Establece permisos y directorio de trabajo
WORKDIR /var/www/html

# Expone el puerto 80 para HTTP
EXPOSE 80
