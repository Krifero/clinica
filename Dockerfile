FROM php:8.2-fpm

# Instalar dependencias del sistema esenciales y Nginx
RUN apt-get update && apt-get install -y \
    nginx \
    bash \
    git \
    unzip \
    libpq-dev \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Descargar la herramienta oficial e infalible para instalar extensiones de PHP
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

# Instalar TODAS las extensiones nativas que Laravel exige para funcionar de forma estable
RUN chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions pdo pdo_mysql pdo_pgsql bcmath ctype fileinfo json mbstring openssl xml zip tokenizer

# Configurar directorio de trabajo
WORKDIR /var/www/html
COPY . .

# Instalar Composer de forma limpia y ejecutar instalación optimizada sin caché
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-dev --optimize-autoloader --no-cache

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

# Arrancar el contenedor: ejecuta migraciones y enciende servidores
CMD php artisan migrate --force; php artisan db:seed --force; php-fpm -D && nginx -g "daemon off;"