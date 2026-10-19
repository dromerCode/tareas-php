# Gestor de tareas (PHP + MariaDB)

Mismo esquema que la tienda: `index.php` → `controllers/mainController.php`, que según `?page=` carga el controlador, que usa los repositorios (mysqli con sentencias preparadas) y acaba en una vista `.phtml`.

## Arrancar

```bash
cp .env.example .env        # y cambia las contraseñas
docker compose up -d        # MariaDB en el puerto 3308, importa db/schema.sql y db/datos.sql
php -S localhost:8000
```

Usuarios de prueba (contraseña `1234`): `admin@tareas.test` (admin), `ana@tareas.test`, `luis@tareas.test`.

## Qué hace

- Login, registro y cerrar sesión.
- Cualquier usuario crea tareas y las asigna a alguien (o a nadie).
- Estados: **pendiente → aceptada → completada**. Acepta quien la tiene asignada (o cualquiera si no es de nadie, y se la queda); la completa quien la aceptó.
- Editar y borrar: quien creó la tarea o el admin.
- El admin ve todas las tareas y gestiona usuarios (hacer/quitar admin, borrar).
