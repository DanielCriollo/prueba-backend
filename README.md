# Prueba Técnica Backend

Este proyecto es una implementación de una API RESTful para la gestión de productos y precios, desarrollada con Laravel 12. Incluye autenticación mediante JWT, documentación interactiva con Swagger y sigue buenas prácticas de desarrollo como el patrón Repository-Service.

## 🚀 Tecnologías y Herramientas

*   **Framework**: Laravel 12.x
*   **Lenguaje**: PHP 8.2+
*   **Base de Datos**: MySQL
*   **Autenticación**: JWT (JSON Web Tokens) via `tymon/jwt-auth`
*   **Documentación**: Swagger / OpenAPI via `darkaonline/l5-swagger`
*   **Testing**: PHPUnit / Laravel Feature Tests

## 🏗 Arquitectura y Patrones

El proyecto implementa una arquitectura limpia y modular:
*   **Repository Pattern**: `EloquentProductRepository` para abstraer la lógica de acceso a datos.
*   **Service Layer**: `ProductService` para encapsular la lógica de negocio.
*   **API Resources**: Para transformar y estandarizar las respuestas JSON.
*   **Form Requests**: Para la validación de datos de entrada.
*   **Traits**: `ApiResponse` para unificar el formato de respuestas de éxito y error.

## 🛠 Instalación y Configuración

Sigue estos pasos para levantar el proyecto en tu entorno local:

1.  **Clonar el repositorio**
    ```bash
    git clone https://github.com/DanielCriollo/prueba-backend
    cd prueba-backend
    ```

2.  **Instalar dependencias de PHP**
    ```bash
    composer install
    ```

3.  **Configurar variables de entorno**
    ```bash
    cp .env.example .env
    ```
    Edita el archivo `.env` y configura tus credenciales de base de datos:
    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=prueba_backend
    DB_USERNAME=root
    DB_PASSWORD=
    ```

4.  **Generar claves de la aplicación**
    ```bash
    php artisan key:generate
    php artisan jwt:secret
    ```

5.  **Ejecutar migraciones y seeders**
    Esto creará las tablas y usuarios de prueba.
    ```bash
    php artisan migrate --seed
    ```

6.  **Generar documentación de Swagger**
    ```bash
    php artisan l5-swagger:generate
    ```

7.  **Levantar el servidor**
    ```bash
    php artisan serve
    ```
    O si usas Laragon/Valet, accede directamente a través del host virtual (ej. `http://prueba-backend.test`).

## 🔑 Autenticación y Uso

La API está protegida por JWT. Para acceder a los endpoints de productos, primero debes autenticarte.

### Usuarios de Prueba (Seeders)
*   **Email**: `user1@example.com`
*   **Password**: `password123`

### Flujo de Autenticación
1.  Haz una petición `POST` a `/api/auth/login` con las credenciales.
2.  Recibirás un `access_token`.
3.  Usa este token en el header `Authorization` de tus siguientes peticiones:
    ```text
    Authorization: Bearer <tu_token_aqui>
    ```

## 📚 Documentación y Consumo de API

### 1. Swagger UI (Navegador)
Esta es la forma más rápida de ver y probar la API sin instalar nada extra.

1.  **Generar la documentación** (si hiciste cambios en el código):
    ```bash
    php artisan l5-swagger:generate
    ```
2.  **Abrir en el navegador**:
    *   [http://localhost:8000/api/documentation](http://localhost:8000/api/documentation)

### 2. Postman
Para importar la colección completa en Postman:

1.  Abre Postman.
2.  Haz clic en el botón **"Import"** (arriba a la izquierda).
3.  Selecciona o arrastra el archivo:
    *   Opción A (Recomendada): `openapi.json` (ubicado en la raíz del proyecto).
    *   Opción B (Fuente original): `storage/api-docs/api-docs.json` (generado por artisan).

### 3. Insomnia
Insomnia soporta nativamente el formato OpenAPI.

1.  Abre Insomnia.
2.  Haz clic en **"Create"** -> **"Import from File"**.
3.  Selecciona el mismo archivo `openapi.json` de la raíz (o `storage/api-docs/api-docs.json`).

---

### Endpoints Principales
*   `POST /api/auth/login`: Iniciar sesión.
*   `GET /api/products`: Listar productos (paginado).
*   `POST /api/products`: Crear producto.
*   `GET /api/products/{id}`: Ver producto.
*   `PUT /api/products/{id}`: Actualizar producto.
*   `DELETE /api/products/{id}`: Eliminar producto.
*   `POST /api/products/{id}/prices`: Agregar precio en otra moneda.

## 🐳 Docker

El proyecto está totalmente dockerizado.

1.  **Construir y levantar contenedores**
    ```bash
    docker-compose up -d --build
    ```

2.  **Instalar dependencias dentro del contenedor**
    ```bash
    docker-compose exec app composer install
    ```

3.  **Ejecutar migraciones y seeders**
    ```bash
    docker-compose exec app php artisan migrate --seed
    ```

4.  **Generar JWT Secret**
    ```bash
    docker-compose exec app php artisan jwt:secret
    ```

5.  **Acceso**
    La API estará disponible en `http://localhost:8000`.

## ✅ Ejecución de Tests

El proyecto incluye tests de integración (Feature Tests) que cubren el flujo completo de la API de productos.

Para ejecutar los tests:
```bash
php artisan test
```