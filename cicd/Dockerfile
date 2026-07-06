# Khai báo image de build tu do - NÂNG CẤP LÊN PHP 8.3
FROM php:8.3-fpm  

# Cài đặt Dependencies
# Cập nhật danh sách package của Ubuntu
# Tải và giải nén các extension , nginx , process manager để chạy cả nginx và php-fpm trong 1 contaienr 
# Cài các extension trong docker
# dọn dẹp để giảm kích thước

RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libonig-dev libxml2-dev libzip-dev nginx supervisor \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/* 

# Cài Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer    

# Đường dẫn chuẩn cho web apps
WORKDIR /var/www/html

#Copy code toàn bộ vào image
COPY . .

# Cài đặt Laravel Dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Phân quyền cho Laravel 
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Cấu hình Nginx
RUN printf 'server {\n\
    listen 80;\n\
    root /var/www/html/public;\n\
    index index.php;\n\
    location / { try_files $uri $uri/ /index.php?$query_string; }\n\
    location ~ \.php$ {\n\
    fastcgi_pass 127.0.0.1:9000;\n\
    fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;\n\
    include fastcgi_params;\n\
    }\n\
    }' > /etc/nginx/sites-available/default

# Cấu hình Supervisor để chạy cả Nginx + PHP-FPM
RUN printf '[supervisord]\n\
    nodaemon=true\n\
    [program:php-fpm]\n\
    command=php-fpm\n\
    [program:nginx]\n\
    command=nginx -g "daemon off;"\n' > /etc/supervisor/conf.d/laravel.conf

EXPOSE 80

# Copy entrypoint script
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Set entrypoint
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/supervisord.conf"]

# 1. Container khởi động
#       ↓
# 2. Supervisor chạy (CMD)
#       ↓
# 3. Supervisor khởi động Nginx (port 80) + PHP-FPM (port 9000)
#       ↓
# 4. User truy cập http://localhost:80
#       ↓
# 5. Nginx nhận request → Nếu là .php → chuyển đến PHP-FPM
#       ↓
# 6. PHP-FPM xử lý code Laravel → Trả về HTML
#       ↓
# 7. Nginx gửi response về user