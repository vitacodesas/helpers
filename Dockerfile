# Usa una imagen oficial de PHP con Composer
FROM php:8.2-cli

# Instala dependencias
RUN apt-get update && apt-get install -y \
    zip \
    unzip \
    git \
    && docker-php-ext-install pdo pdo_mysql

# # Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Crea el directorio de la app
WORKDIR /app

COPY . .

RUN ls -la

# Expone el puerto 8000 para el servidor Laravel
EXPOSE 8000

# Comando por defecto
CMD ["php", "-S", "0.0.0.0:8000", "-t", "testbench/public"]