
# Logística y Transporte de Donaciones

Guia de Instalacion en Docker y Nativo para Web y Movil.
Soporta ejecución:
- **Docker (Docker Compose + Nginx + PostgreSQL)**
- **Local/Nativa (PHP + PostgreSQL en tu PC)**
- **Móvil (React Native)**

---

## Requisitos (links oficiales)

### Web (Laravel)
- Docker Desktop: [Get Started](https://docs.docker.com/get-started/)
- Git: [Instalación](https://git-scm.com/install/windows)
- PHP: [Instala para tu OS](https://www.php.net/manual/en/install.php)
- Composer: [Instalación por CMD o Manual](https://getcomposer.org/download/)
- PostgreSQL:[Instalación](https://www.postgresql.org/download/)
- Laravel (requisitos/instalación): [Laravel 12 es la version utilizada en el proyecto](https://laravel.com/docs/12.x/installation) **NOTA: Debes tener Composer y PHP instalados previamente**
- DBeaver (opcional): [Visualizador de Base de Datos ligero](https://dbeaver.io/download/)

### Móvil (React Native)
- React Native (docs): [Guia de Setup del entorno](https://reactnative.dev/docs/environment-setup)
- Expo Go: [Guia de instalacion](https://docs.expo.dev/get-started/create-a-project/)

---

## 1) Levantar Backend con Docker (recomendado) 🐳

Esta opción levanta:
- **Laravel (PHP-FPM)**
- **Nginx**
- **PostgreSQL**

### 1.1 Clonar el repositorio
`git clone <URL_DEL_REPO>`
`cd <CARPETA_DEL_REPO>`
## 1.2 Variables de entorno (.env)

Crea tu `.env` desde el ejemplo:

- `cp .env.example .env`

**Nota importante:** en Docker, el host de Postgres normalmente debe ser el nombre del servicio:

- `DB_HOST=db`

---

## 1.3 Redes externas (IMPORTANTE)

Tu `docker-compose.yml` usa redes externas:
## 1.3 Redes externas (IMPORTANTE)

Tu `docker-compose.yml` usa redes externas:

- `internal-network`
- `proxy-network`

Si no existen en tu Docker, créalas:

- `docker network create internal-network`
- `docker network create proxy-network`

---

## 1.4 Exponer Nginx al host (acceso desde tu PC)

Si tu `docker-compose.yml` tiene comentado el mapeo de puertos, descoméntalo y deja algo como:

- `ports:`
  - `"8080:80"`

---

## 1.5 Levantar contenedores

- `docker compose up -d --build`

**Sobre el `entrypoint.sh`:** en este proyecto el arranque puede ejecutar tareas como:

- `composer install`
- `php artisan key:generate`
- `php artisan migrate`
- `php artisan db:seed`
- `php artisan storage:link`

El `entrypoint.sh` hace esto automáticamente, no necesitas ejecutarlas manualmente.

---

## 1.6 Acceso

- Con puerto local: `http://localhost:8080`
- Detrás de reverse proxy: dependerá del dominio configurado.
## 1.7 Comandos útiles (Docker)

**Estado:**
- `docker compose ps`

**Logs:**
- `docker compose logs -f laravel`
- `docker compose logs -f nginx`
- `docker compose logs -f db`

**Entrar al contenedor Laravel:**
- `docker compose exec laravel bash`

**Ejecutar Artisan:**
- `docker compose exec laravel php artisan route:list`
- `docker compose exec laravel php artisan migrate`
- `docker compose exec laravel php artisan db:seed`

**Reiniciar desde cero (incluye volumen de DB):**
- `docker compose down -v`
- `docker compose up -d --build`

---

## 2) Levantar Backend en Local/Nativo 💻

### 2.1 Clonar el repositorio
- `git clone <URL_DEL_REPO>`
- `cd <CARPETA_DEL_REPO>`

### 2.2 Variables de entorno (.env)
- `cp .env.example .env`

Edita `.env` (ejemplo típico local):
- `DB_HOST=127.0.0.1`
- `DB_PORT=5432`
- `DB_DATABASE=<TU_BD>`
- `DB_USERNAME=<TU_USUARIO>`
- `DB_PASSWORD=<TU_PASSWORD>`

### 2.3 Instalar dependencias PHP
- `composer install`

### 2.4 Generar APP_KEY
- `php artisan key:generate`

### 2.5 Crear base de datos y correr migraciones/seeders
- Crea la BD en PostgreSQL con el nombre de `DB_DATABASE`.
- Corre:
  - `php artisan migrate`
  - `php artisan db:seed`

**Nota:** si usas `SESSION_DRIVER=database` y/o `QUEUE_CONNECTION=database`, es obligatorio correr migraciones para crear tablas de sesiones/colas.

### 2.6 Storage link y permisos
- `php artisan storage:link`

Si hay problemas de permisos (Linux/macOS):
- `chmod -R 777 storage bootstrap/cache`

### 2.7 Levantar servidor
- `php artisan serve --host=127.0.0.1 --port=8000`

Accede en:
- `http://localhost:8000`
## 3) Desplegar / Ejecutar la app Móvil (React Native) 📱

Esta sección asume que ya tienes el backend levantado (Docker o local) y que tu app móvil consume la API mediante una variable/configuración (por ejemplo `API_BASE_URL`).

---

### 3.1 Configurar URL del Backend para el móvil (IMPORTANTE)

Define la URL base del backend en tu app móvil dentro del archivo `apiConfig.js`.

Ejemplos típicos:

- Si el backend corre en tu PC con Docker y puerto 8080:  
  - `http://<IP_DE_TU_PC>:8080`

- Si el backend corre en tu PC con `php artisan serve` en 8000:  
  - `http://<IP_DE_TU_PC>:8000`

---

### 3.2 Ejecutar en Android

- Instala la app Expo Go
- Dentro del proyecto móvil:
  - Ejecuta el comando `npx expo start` y espera a ver un codigo QR
  - Dentro de la app Expo Go en tu dispositivo selecciona escanear código QR

---

### 3.3 Ejecutar en iOS
- Instala la app Expo Go
- Dentro del proyecto móvil:
  - Ejecuta el comando `npx expo start` y espera a ver un codigo QR
  - En tu dispositivo con expo go utiliza la camara para escanear el codigo QR de la consola

---

## Endpoints externos (configuración del Backend)

Este proyecto depende de URLs/keys externas (Gateway, Inventario, Hotspot, Animales, Helpdesk). Revisa en tu `.env` y reemplazalos por las url correctas:

- `HELPDESK_API_URL`
- `HELPDESK_API_KEY`
- `GATEWAY_REGISTRO_SIMPLE_URL`
- `INVENTARIO_API_URL`
- `HOTSPOT_API_URL`
- `ANIMALES_API_URL`
- `GATEWAY_API_URL`

