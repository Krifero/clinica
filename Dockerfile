FROM php:8.2-fpm

# Instalar Nginx y extensiones necesarias para PostgreSQL y MySQL de forma nativa
RUN apt-get update && apt-get install -y \
    nginx \
    bash \
    libpq-dev \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Configurar directorio de trabajo
WORKDIR /var/www/html
COPY . .

# Instalar dependencias de PHP con Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-dev --optimize-autoloader

# Configurar permisos requeridos por Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Configurar Nginx para Laravel
RUN echo 'server { \
    listen 80; \
    root /var/www/html/public; \
    index index.php index.html; \
    location / { \
        try_files $uri $uri/ /index.php?$query_string; \
    } \
    location ~ \.php$ { \
        include fastcgi_params; \
        fastcgi_pass 127.0.0.1:9000; \
        fastcgi_index index.php; \
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name; \
    } \
}' > /etc/nginx/sites-available/default \
&& ln -sf /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default

# Exponer puerto web estándar
EXPOSE 80

# Arrancar el contenedor: ejecuta migraciones en la BD de Render y enciende servidores
CMD php artisan migrate --force && php-fpm -D && nginx -g "daemon off;"