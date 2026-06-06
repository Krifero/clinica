FROM php:8.2-fpm-alpine

# Instalar extensiones de PHP necesarias para Laravel y herramientas
RUN apk add --no-cache nginx wget bash nodejs npm \
    && docker-php-ext-install pdo pdo_mysql

# Configurar directorio de trabajo
WORKDIR /var/www/html
COPY . .

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-dev --optimize-autoloader

# Configurar permisos para Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Configurar Nginx corregido con soporte de rutas para Laravel
RUN echo 'server { \
    listen 80; \
    root /var/www/html/public; \
    index index.php index.html; \
    location / { \
        try_files $uri $uri/ /index.php?$query_string; \
    } \
    location ~ \.php$ { \
        try_files $uri =404; \
        include fastcgi_params; \
        fastcgi_pass 127.0.0.1:9000; \
        fastcgi_index index.php; \
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name; \
    } \
}' > /etc/nginx/http.d/default.conf

# Exponer el puerto y arrancar Nginx junto con PHP
EXPOSE 80
CMD php artisan migrate --force && php-fpm -D && nginx -g "daemon off;"