FROM php:8.3-fpm

# Cài đặt system dependencies cần thiết
RUN apt-get update && apt-get install -y \
    nginx supervisor \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

# 1. Copy toàn bộ source code (bao gồm cả vendor và public/build đã được Jenkins tạo sẵn)
# .dockerignore sẽ tự động loại bỏ node_modules, .git, v.v.
COPY . .

# 2. Phân quyền Laravel (bao gồm cả thư mục vendor và public/build)
RUN chown -R www-data:www-data /var/www/html/storage \
    /var/www/html/bootstrap/cache \
    /var/www/html/vendor \
    /var/www/html/public/build \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 3. Cấu hình Nginx
RUN printf 'server {\n\
    listen 80;\n\
    root /var/www/html/public;\n\
    index index.php index.html;\n\
    location / { try_files $uri $uri/ /index.php?$query_string; }\n\
    location ~ \.php$ {\n\
    fastcgi_pass 127.0.0.1:9000;\n\
    fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;\n\
    include fastcgi_params;\n\
    }\n\
    }' > /etc/nginx/sites-available/default

# 4. Cấu hình Supervisor
RUN printf '[supervisord]\n\
    nodaemon=true\n\
    [program:php-fpm]\n\
    command=php-fpm\n\
    [program:nginx]\n\
    command=nginx -g "daemon off;"\n' > /etc/supervisor/conf.d/laravel.conf

EXPOSE 80

# 5. Entrypoint
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/supervisord.conf"]