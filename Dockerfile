# Utilizamos una imagen oficial de PHP 8.1 como imagen base
FROM php:8.4-apache

# Instalamos los paquetes necesarios
RUN apt-get update && apt-get upgrade -y && apt-get install -y \
    libpq-dev \
    libicu-dev \
    git \ 
    curl \
    vim \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# Configuramos los módulos de PHP
RUN docker-php-ext-configure intl
RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql intl

# Instalamos y habilitamos Xdebug
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug


# Habilita mod_rewrite
RUN a2enmod rewrite
RUN a2enmod ssl

# 2. Copiar nuestra configuración personalizada (la que creamos en el Paso A)
# La copiamos encima de la "000-default.conf" para que sea la que mande.
COPY ./config/default-ssl.conf /etc/apache2/sites-available/default-ssl.conf

RUN a2dissite 000-default && a2ensite default-ssl

# Establecemos la raíz web de Apache en el directorio público del proyecto
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

# Copiamos nuestra aplicación a la carpeta de trabajo del contenedor
COPY . /var/www/html/
COPY web/apache2.conf /etc/apache2/apache2.conf

# Establecemos la carpeta de trabajo
WORKDIR /var/www/html/

# Ejecutamos los comandos necesarios para instalar las dependencias de PHP y ejecutar nuestro proyecto
RUN curl -sS https://getcomposer.org/installer | php \
    && php composer.phar install \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 777 /var/www/html/var
    
# Copiamos configuraci�n de Xdebug
# COPY ./xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini


# Exponemos el puerto 80 para el tráfico HTTP
EXPOSE 80 443

# Iniciamos el servidor Apache en primer plano
CMD ["apache2-foreground"]
