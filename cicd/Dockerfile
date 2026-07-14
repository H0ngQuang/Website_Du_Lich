# ==========================================
# STAGE 1: Builder (Dùng để cài đặt và build)
# ==========================================
FROM php:8.3-cli AS builder

# Cài đặt các gói cần thiết để build extension PHP
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libonig-dev libxml2-dev libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Cài Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Cài Node.js 18 để build Vite (Frontend)
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

# Copy file cấu hình dependency trước để tận dụng Docker Cache
COPY composer.json composer.lock package.json package-lock.json ./

# Cài đặt PHP dependencies và Node dependencies, sau đó build frontend
RUN composer install --no-interaction --no-dev --optimize-autoloader \
    && npm ci \
    && npm run build

# Copy toàn bộ source code (những file bị ignore trong .dockerignore sẽ không được copy)
COPY . .

# ==========================================
# STAGE 2: Production Runtime (Nhẹ & Bảo mật)
# ==========================================
FROM php:8.3-fpm AS production

# Chỉ cài đặt runtime cần thiết (KHÔNG có composer, nodejs, git...)
RUN apt-get update && apt-get install -y \
    nginx supervisor \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

# Copy chỉ những thứ CẦN THIẾT từ stage builder sang
COPY --from=builder /var/www/html/vendor ./vendor
COPY --from=builder /var/www/html/public/build ./public/build
COPY --from=builder /var/www/html/bootstrap/cache ./bootstrap/cache
COPY --from=builder /var/www/html/storage ./storage

# Copy source code chính (app, config, routes, views...)
COPY --chown=www-data:www-data . .

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

# Copy entrypoint script (nếu bạn có file này trong thư mục cicd/docker/)
# Nếu không có, hãy xóa 2 dòng dưới đi hoặc tạo file entrypoint.sh tương ứng
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/supervisord.conf"]