
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
