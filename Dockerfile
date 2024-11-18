# Usa una imagen base de PHP con Apache
FROM php:8.2-apache

# Habilitar el módulo de Apache rewrite
RUN a2enmod rewrite

# Establecer el directorio de trabajo en el contenedor
WORKDIR /var/www/html

# Instalar las dependencias del proyecto usando Composer
RUN composer install --no-dev --optimize-autoloader

# Exponer el puerto 80 para la aplicación
EXPOSE 80

# Cambiar la configuración de Apache para permitir index.php como el archivo predeterminado
RUN echo "DirectoryIndex index.php" >> /etc/apache2/apache2.conf