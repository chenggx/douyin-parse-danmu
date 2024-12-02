# 使用 PHP 7.2 CLI 官方镜像
FROM php:7.2-cli

# 设置工作目录
WORKDIR /var/www

# 安装系统依赖
RUN apt-get update && apt-get install -y \
    curl \
    git
# 安装 PHP 扩展
RUN docker-php-ext-install pcntl bcmath

# 安装 Node.js 和 npm
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs

# 安装 Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 复制项目文件到容器
COPY . .

# 安装 PHP 和 Node.js 依赖
RUN composer install --no-dev && cd websdk && npm install


# 开放必要的端口 (例如 Webman 默认监听的端口)
EXPOSE 4200

# 复制启动脚本
COPY start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

# 使用启动脚本
CMD ["sh", "/usr/local/bin/start.sh"]

