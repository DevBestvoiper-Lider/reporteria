FROM php:8.2-apache

# Instalar extensiones PHP necesarias
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo \
        pdo_mysql \
        mysqli \
        gd \
        zip \
        opcache

# Habilitar mod_rewrite y mod_headers de Apache
RUN a2enmod rewrite headers

# Configurar PHP
RUN echo "memory_limit = 512M" > /usr/local/etc/php/conf.d/memory-limit.ini \
    && echo "upload_max_filesize = 64M" > /usr/local/etc/php/conf.d/upload-max-filesize.ini \
    && echo "post_max_size = 64M" > /usr/local/etc/php/conf.d/post-max-size.ini \
    && echo "max_execution_time = 300" > /usr/local/etc/php/conf.d/max-execution-time.ini

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurar el DocumentRoot
ENV APACHE_DOCUMENT_ROOT=/var/www/html/reporteria

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Crear directorio de trabajo
WORKDIR /var/www/html/reporteria

# Copiar archivos de la aplicación
COPY . .

# Instalar dependencias de Composer (si existen)
RUN if [ -f composer.json ]; then composer install --no-dev --optimize-autoloader; fi

# Dar permisos apropiados
RUN chown -R www-data:www-data /var/www/html/reporteria \
    && chmod -R 755 /var/www/html/reporteria

# Exponer puerto 80
EXPOSE 80

# Comando de inicio
CMD ["apache2-foreground"]
