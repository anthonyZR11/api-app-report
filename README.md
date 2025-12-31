# API App Report

Esta es la API backend que consumirá el frontend para gestionar el sistema y realizar la autenticación de usuarios. Provee endpoints para registro, login y generación de reportes.

## 🛠 Tecnologías Usadas

*   ![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white) **Laravel 11**
*   ![MySQL](https://img.shields.io/badge/MySQL-00000F?style=for-the-badge&logo=mysql&logoColor=white) **MySQL 8.0**
*   ![Docker](https://img.shields.io/badge/Docker-2496ED?style=for-the-badge&logo=docker&logoColor=white) **Docker Compose**
*   ![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white) **PHP 8.2**

---

## 🚀 Instalación y Ejecución en Desarrollo

### Requisitos Previos
*   PHP >= 8.2
*   Composer
*   MySQL

### Pasos
1.  **Instalar dependencias:**
    ```bash
    composer install
    ```
2.  **Configurar variables de entorno:**
    ```bash
    cp .env.example .env
    ```
    Configura tu base de datos en el archivo `.env`.
3.  **Generar key de la aplicación:**
    ```bash
    php artisan key:generate
    ```
4.  **Correr migraciones y seeders:**
    ```bash
    php artisan migrate --seed
    ```
5.  **Levantar el servidor:**
    ```bash
    php artisan serve
    ```

---

## 🐳 Docker Compose

Para levantar el entorno completo (App + Base de Datos) con Docker:

```bash
docker-compose up -d --build
```

### Comandos útiles en Docker
*   **Entrar a la terminal del servicio:**
    ```bash
    docker exec -it api-report bash
    ```
*   **Ejecutar migraciones**
    ```bash
    php artisan migrate
    ```

     **Ejecutar seeder**
    ```bash
    php artisan db:seed --class=UserSeeder
    ```
    *(Nota: Debes estar dentro del contenedor o usar)*

---

## 🔒 Seguridad Integrada

La API cuenta con varias capas de seguridad:

*   **Autenticación**: Implementada vía `Sanctum`. Los endpoints protegidos requieren un token Bearer.
*   **CORS**: Configurado para permitir peticiones desde el frontend.
*   **Rate Limiting** (Límite de peticiones):
    *   `auth`: **5 peticiones por minuto** para login/registro (previene fuerza bruta).
    *   `api`: **30 peticiones por minuto** para endpoints generales.

---

## 📡 Endpoints

### Autenticación

#### `POST /api/login`
Inicia sesión y obtiene un token de acceso.
*   **Body:**
    *   `email`: (required, email)
    *   `password`: (required, string, min:6)

#### `POST /api/register`
Registra un nuevo usuario.
*   **Body:**
    *   `name`: (required, string)
    *   `email`: (required, email, unique)
    *   `password`: (required, string, min:6)
    *   `birth_date`: (required, date)

### Reportes (Requiere Autenticación)
Headers requeridos: `Authorization: Bearer <token>`

#### `POST /api/generate-report`
Genera un nuevo reporte en base a un rango de fechas.
*   **Body:**
    *   `title`: (required, string, unique)
    *   `start_date`: (required, date)
    *   `end_date`: (required, date, debe ser posterior a start_date)

#### `GET /api/list-reports`
Obtiene el listado de todos los reportes generados.

#### `GET /api/get-report/{report_id}`
Descarga un reporte específico.

---

## ❌ Códigos de Estado y Errores

La API utiliza los siguientes códigos HTTP estándar:

| Código | Descripción | Significado |
| :--- | :--- | :--- |
| **200** | OK | Petición exitosa. |
| **201** | Created | Recurso creado exitosamente. |
| **400** | Bad Request | La petición es incorrecta. |
| **401** | Unauthorized | Token inválido o no enviado. |
| **404** | Not Found | Recurso no encontrado (Ej. reporte inexistente). Respuesta custom: `{ "ok": false, "message": "Recurso no encontrado", ... }` |
| **409** | Conflict | Conflicto de datos (Ej. duplicados). Respuesta custom: `{ "ok": false, "message": "Valor duplicado", ... }` |
| **422** | Unprocessable Entity | Error de validación en los campos enviados. |
| **429** | Too Many Requests | Excediste el límite de peticiones (Rate Limit). |


(**Nota:** Por temas de practicidad se permitio la exposición del archivo .env)


**Url en local:** http://127.0.0.1:8000/
**Url en producción:** http://161.132.45.52:8000/