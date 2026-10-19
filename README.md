# Gestor de tareas (PHP + MariaDB)

Como el ejemplo del día 4: `index.php` carga `controllers/mainController.php`, que lo hace todo por secciones
(registro, login, aceptar/completar, crear/editar/borrar) mirando qué llega por `$_POST`, y al final carga una vista.
Las vistas se eligen con `?var=`: `2` registro, `4&id=...` editar tarea (admin). Sin sesión se ve el login.

## Arrancar

```bash
cp .env.example .env        # y cambia las contraseñas
docker compose up -d        # MariaDB en el puerto 3308, importa db/schema.sql y db/datos.sql
php -S localhost:8000
```

Usuarios de prueba (contraseña `1234`): `admin@tareas.test` (admin), `ana@tareas.test`, `luis@tareas.test`.

## Qué hace

- **Admin**: ve todas las tareas, las crea asignándolas a un usuario, las edita y las borra.
- **Usuario**: solo ve sus tareas y las pasa de *pendiente* a *aceptada* y luego a *completada*.
