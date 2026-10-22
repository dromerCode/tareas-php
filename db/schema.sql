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

-- Categorías para etiquetar las tareas
CREATE TABLE categoria (
  id_categoria  INT UNSIGNED NOT NULL AUTO_INCREMENT,
  nombre        VARCHAR(50)  NOT NULL UNIQUE,
  PRIMARY KEY (id_categoria)
);

-- Tareas. Cada tarea está asignada a un usuario y puede tener una categoría
CREATE TABLE tarea (
  id_tarea    INT UNSIGNED NOT NULL AUTO_INCREMENT,
  titulo      VARCHAR(150) NOT NULL,
  descripcion TEXT NULL,
  estado      ENUM('pendiente', 'aceptada', 'completada') NOT NULL DEFAULT 'pendiente',
  id_usuario  INT UNSIGNED NOT NULL,
  -- NULL = sin categoría
  id_categoria INT UNSIGNED NULL,
  PRIMARY KEY (id_tarea),
  -- Si se borra el usuario, se borran sus tareas
  FOREIGN KEY (id_usuario) REFERENCES usuario (id_usuario) ON DELETE CASCADE,
  -- Si se borra la categoría, la tarea se queda sin categoría
  FOREIGN KEY (id_categoria) REFERENCES categoria (id_categoria) ON DELETE SET NULL
);
