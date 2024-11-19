# Sistema de Gestión de Usuarios y Posts - API REST  

Este proyecto es una API RESTful desarrollada con **Slim Framework 4** y utiliza **JSON Web Tokens (JWT)** para la autenticación. La API permite gestionar usuarios y posts, proporcionando un sistema básico de autenticación y autorización.  

---

## Características  
- **Autenticación JWT**: Inicio de sesión seguro con tokens de acceso.  
- **Gestión de Usuarios**: Crear, leer, actualizar y eliminar usuarios.  
- **Gestión de Posts**: Crear, leer, actualizar y eliminar posts vinculados a usuarios.  
- **Estructura RESTful**: Rutas organizadas y métodos HTTP estándar.  

---

## Requisitos Previos  
Antes de comenzar, asegúrate de tener instalado:  
- PHP >= 8.1  
- Composer  
- MySQL o cualquier otro sistema de base de datos soportado.  
- Postman o cURL para pruebas.  

---

## Instalación  

### 1. Clonar el Repositorio  
Clona este repositorio en tu máquina local:  
```bash  
git clone https://github.com/johanS123/edulabs-api.git  
cd tu-repositorio
```

### 2. Instalar Dependencias
Para instalar las dependencias del proyecto, asegúrate de tener instalado **Composer** y ejecuta el siguiente comando en el directorio raíz del proyecto:  
```bash  
composer install
```

### 3. Configuración  

El proyecto requiere un archivo `.env` para configurar las variables de entorno necesarias. Este archivo debe crearse en la raíz del proyecto y debe contener las siguientes variables:  

```env  
DB_HOST=localhost  
DB_NAME=nombre_base_de_datos  
DB_USER=usuario_base_de_datos  
DB_PASS=contraseña_base_de_datos  
JWT_SECRET=tu_secreto_jwt  
```

## Endpoints  

A continuación, se presenta una lista de los endpoints disponibles en la API:  

### Autenticación  
| Método | Endpoint         | Descripción                         | Autenticación |  
|--------|------------------|-------------------------------------|---------------|  
| POST   | `/api/register`  | Registra un nuevo usuario.          | No            |  
| POST   | `/api/login`     | Inicia sesión y genera un token JWT.| No            |  

### Usuarios  
| Método | Endpoint         | Descripción                         | Autenticación |  
|--------|------------------|-------------------------------------|---------------|  
| GET    | `/api/users`     | Obtiene una lista de todos los usuarios. | Sí       |  
| GET    | `/api/users/{id}`| Obtiene un usuario específico por ID.| Sí       |  
| PUT    | `/api/users/{id}`| Actualiza la información de un usuario. | Sí    |  
| DELETE | `/api/users/{id}`| Elimina un usuario por ID.           | Sí            |  

### Posts  
| Método | Endpoint         | Descripción                         | Autenticación |  
|--------|------------------|-------------------------------------|---------------|  
| GET    | `/api/posts`     | Obtiene una lista de todos los posts.| Sí           |  
| GET    | `/api/posts/{categoryid}`| Obtiene un post específico por el id de la categoria.   | Sí           |  
| POST   | `/api/posts`     | Crea un nuevo post.                 | Sí            |  
| PUT    | `/api/posts/{id}`| Actualiza un post existente por ID. | Sí            |  
| DELETE | `/api/posts/{id}`| Elimina un post por ID.             | Sí            |  

---

### Notas:  
- Los endpoints marcados con **Autenticación: Sí** requieren un encabezado con el token JWT:  
  ```http  
  Authorization: Bearer <tu-token>  

### 4. Iniciar el Servidor  

Para iniciar el servidor en tu entorno local, utiliza el servidor embebido de PHP con el siguiente comando:  
```bash  
php -S localhost:8000 -t public  
