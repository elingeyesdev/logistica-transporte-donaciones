#!/bin/bash

# Salir si algún comando falla
set -e

# Crear .env si no existe
if [ ! -f .env ]; then
    echo "No existe .env — creando desde .env.example"
    cp .env.example .env
else
    echo "✔️ Archivo .env ya existe — no se copia"
fi

echo "Instalando dependencias de Composer..."
composer install --no-interaction --prefer-dist --optimize-autoloader
composer require lukehowland/helpdeskwidget

echo "Generando APP_KEY (si no existe)..."
php artisan key:generate --force || true

echo "Aplicando permisos..."
chmod -R 777 storage bootstrap/cache

echo "Ejecutando migraciones..."
php artisan migrate --force || true

echo "Ejecutando Seeder..."
php artisan db:seed --force || true

echo "Creando symlink de storage..."
php artisan storage:link || true  

echo "🚀 Iniciando PHP-FPM..."
exec php-fpm
