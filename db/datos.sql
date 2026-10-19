-- Datos de prueba del gestor de tareas
-- Los tres usuarios tienen la contraseña 1234

USE tareas;

INSERT INTO usuario (nombre, apellido, email, contrasena, rol) VALUES
  ('Admin', 'Tareas', 'admin@tareas.test', '$2y$12$1iDFNcISWOGtuL7l4/IiPeV4N.SKcTbhKtluu.OtrY4QcNA5owATC', 'admin'),
  ('Ana', 'García', 'ana@tareas.test', '$2y$12$1iDFNcISWOGtuL7l4/IiPeV4N.SKcTbhKtluu.OtrY4QcNA5owATC', 'usuario'),
  ('Luis', 'Pérez', 'luis@tareas.test', '$2y$12$1iDFNcISWOGtuL7l4/IiPeV4N.SKcTbhKtluu.OtrY4QcNA5owATC', 'usuario');

INSERT INTO tarea (titulo, descripcion, estado, fecha_limite, id_creador, id_asignado) VALUES
  ('Preparar la reunión del lunes', 'Hacer el orden del día y mandarlo a todos.', 'pendiente', '2026-10-26', 1, 2),
  ('Revisar el informe de ventas', 'Comprobar que cuadran los números del trimestre.', 'aceptada', '2026-10-30', 1, 3),
  ('Actualizar la web', 'Cambiar los horarios de la página de contacto.', 'completada', NULL, 1, 2),
  ('Comprar material de oficina', 'Folios, bolis y post-its.', 'pendiente', NULL, 2, NULL);
