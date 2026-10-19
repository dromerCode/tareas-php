-- Esquema del gestor de tareas (MariaDB 10.2+)
-- Orden de creación respeta las claves foráneas.

CREATE DATABASE IF NOT EXISTS tareas
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE tareas;

-- ---------------------------------------------------------
-- usuario
-- ---------------------------------------------------------
CREATE TABLE usuario (
  id_usuario  INT UNSIGNED NOT NULL AUTO_INCREMENT,
  nombre      VARCHAR(80)  NOT NULL,
  apellido    VARCHAR(120) NOT NULL,
  email       VARCHAR(120) NOT NULL,
  -- Hash de password_hash(), nunca la contraseña en texto plano
  contrasena  VARCHAR(255) NOT NULL,
  -- admin: ve y gestiona todas las tareas / usuario: solo las suyas
  rol         ENUM('usuario', 'admin') NOT NULL DEFAULT 'usuario',
  PRIMARY KEY (id_usuario),
  UNIQUE KEY uq_usuario_email (email)
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- tarea (usuario 1:N tarea creada, usuario 1:N tarea asignada)
-- ---------------------------------------------------------
CREATE TABLE tarea (
  id_tarea     INT UNSIGNED NOT NULL AUTO_INCREMENT,
  titulo       VARCHAR(150) NOT NULL,
  descripcion  TEXT         NULL,
  -- pendiente: nadie la ha aceptado / aceptada: en ello / completada: hecha
  estado       ENUM('pendiente', 'aceptada', 'completada') NOT NULL DEFAULT 'pendiente',
  fecha_limite DATE         NULL,
  id_creador   INT UNSIGNED NOT NULL,
  -- NULL si todavía no está asignada a nadie
  id_asignado  INT UNSIGNED NULL,
  PRIMARY KEY (id_tarea),
  CONSTRAINT fk_tarea_creador
    FOREIGN KEY (id_creador) REFERENCES usuario (id_usuario)
    ON UPDATE CASCADE
    ON DELETE CASCADE,
  -- Si se borra el usuario asignado, la tarea se queda sin asignar
  CONSTRAINT fk_tarea_asignado
    FOREIGN KEY (id_asignado) REFERENCES usuario (id_usuario)
    ON UPDATE CASCADE
    ON DELETE SET NULL
) ENGINE=InnoDB;
