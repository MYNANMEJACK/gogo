# 使用 PHP 8.1 Apache 版本
FROM php:8.1-apache

# 启用 mysqli 和 PDO 扩展（适用于 MySQL 连接）
RUN docker-php-ext-install pdo pdo_mysql mysqli

# 复制 PHP 文件到 Apache 目录
COPY . /var/www/html/

# 设置工作目录
WORKDIR /var/www/html/

# 暴露端口
EXPOSE 80
