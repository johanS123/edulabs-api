# Usa una imagen base de PHP con Apache
FROM php:8.2-apache

# Instalar dependencias necesarias para Slim y PHP
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql pdo_mysql

# Habilitar el módulo de Apache rewrite
RUN a2enmod rewrite

# Configurar el DocumentRoot a la carpeta public de Slim
RUN sed -i 's|/var/www/html|/var/www/html/public|' /etc/apache2/sites-available/000-default.conf

# Establecer el directorio de trabajo en el contenedor
WORKDIR /var/www/html

# Copiar los archivos del proyecto al contenedor
COPY . .

# Instalar Composer (gestor de dependencias de PHP)
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Instalar las dependencias del proyecto usando Composer
RUN composer install --no-dev --optimize-autoloader

# Exponer el puerto 80 para la aplicación
EXPOSE 80

# Iniciar el servidor Apache
CMD ["apache2-foreground"]
