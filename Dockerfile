FROM php:8.2-fpm

# Instalar Nginx, Node.js, NPM y las librerías oficiales de PostgreSQL
RUN apt-get update && apt-get install -y \
    nginx \
    wget \
    bash \
    nodejs \
    npm \
    libpq-dev \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql

# Configurar directorio de trabajo
WORKDIR /var/www/html
COPY . .

# Instalar dependencias de PHP con Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-dev --optimize-autoloader

# Compilar Frontend (Vite / Mix)
RUN if [ -f package.json ]; then npm install && npm run build; fi

# Configurar permisos para Laravel
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

# Exponer el puerto y arrancar Nginx junto con PHP ejecutando las migraciones automáticas
EXPOSE 80
CMD php artisan migrate --force && php-fpm -D && nginx -g "daemon off;"