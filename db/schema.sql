-- Esquema del gestor de tareas

CREATE DATABASE IF NOT EXISTS tareas
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE tareas;

-- Usuarios. El rol admin puede crear, editar, borrar y ver todas las tareas
CREATE TABLE usuario (
  id_usuario  INT UNSIGNED NOT NULL AUTO_INCREMENT,
  nombre      VARCHAR(80)  NOT NULL,
  email       VARCHAR(120) NOT NULL UNIQUE,
  -- Hash de password_hash(), nunca la contraseña en texto plano
  contrasena  VARCHAR(255) NOT NULL,
  rol         ENUM('usuario', 'admin') NOT NULL DEFAULT 'usuario',
  PRIMARY KEY (id_usuario)
);

-- Tareas. Cada tarea está asignada a un usuario
CREATE TABLE tarea (
  id_tarea    INT UNSIGNED NOT NULL AUTO_INCREMENT,
  titulo      VARCHAR(150) NOT NULL,
  descripcion TEXT NULL,
  estado      ENUM('pendiente', 'aceptada', 'completada') NOT NULL DEFAULT 'pendiente',
  id_usuario  INT UNSIGNED NOT NULL,
  PRIMARY KEY (id_tarea),
  -- Si se borra el usuario, se borran sus tareas
  FOREIGN KEY (id_usuario) REFERENCES usuario (id_usuario) ON DELETE CASCADE
);
