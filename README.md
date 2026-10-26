# Gestor de tareas

Gestor de tareas en PHP + MariaDB con login, registro, roles y categorías.

- **Admin**: ve todas las tareas, las crea, las edita y las borra.
- **Usuario**: solo ve sus tareas y las pasa de *pendiente* a *aceptada* y luego a *completada*.

## Arrancar

1. Copia `.env.example` a `.env` y pon tus contraseñas.
2. `docker compose up -d` (crea la BD con `db/schema.sql` y `db/datos.sql`).
3. `php -S localhost:8000` y abre http://localhost:8000

Usuarios de prueba (contraseña `1234`): `admin@tareas.test` (admin), `ana@tareas.test` y `luis@tareas.test`.
