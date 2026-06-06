FROM shivammathur/node-php:8.2

# Instalar Nginx de forma directa
RUN apt-get update && apt-get install -y nginx && apt-get clean

# Configurar directorio de trabajo
WORKDIR /var/www/html
COPY . .

# Instalar dependencias de PHP usando el Composer que ya viene instalado
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

# Compilar el Frontend (Vite / Mix) con el Node/NPM que ya viene instalado
RUN if [ -f package.json ]; then npm install && npm run build; fi

# Configurar permisos requeridos por Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Configurar Nginx para redireccionar correctamente a Laravel
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

# Iniciar migraciones, PHP-FPM y Nginx
CMD php artisan migrate --force && php-fpm -D && nginx -g "daemon off;"