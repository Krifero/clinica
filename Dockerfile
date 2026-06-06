# --- ETAPA 1: INSTALAR COMPOSER Y CONSTRUIR DEPENDENCIAS ---
FROM composer:2.7 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --ignore-platform-reqs --no-scripts --no-autoloader

# --- ETAPA 2: CONFIGURACIÓN FINAL DEL SERVIDOR DEBIAN ---
FROM php:8.2-fpm

# Instalar dependencias del sistema indispensables
RUN apt-get update && apt-get install -y \
    nginx \
    bash \
    nodejs \
    npm \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql pdo_mysql

# Directorio de trabajo
WORKDIR /var/www/html
COPY . .

# Copiar las dependencias de Composer desde la Etapa 1
COPY --from=vendor /app/vendor/ /var/www/html/vendor/

# Generar el autoloader optimizado de Laravel
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer dump-autoload --optimize --no-dev

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

# Exponer puerto y arrancar servicios con migraciones automáticas
EXPOSE 80
CMD php artisan migrate --force && php-fpm -D && nginx -g "daemon off;"