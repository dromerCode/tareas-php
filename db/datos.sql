-- Datos de prueba. Los tres usuarios tienen la contraseña 1234

USE tareas;

INSERT INTO usuario (nombre, email, contrasena, rol) VALUES
  ('Admin', 'admin@tareas.test', '$2y$12$1iDFNcISWOGtuL7l4/IiPeV4N.SKcTbhKtluu.OtrY4QcNA5owATC', 'admin'),
  ('Ana', 'ana@tareas.test', '$2y$12$1iDFNcISWOGtuL7l4/IiPeV4N.SKcTbhKtluu.OtrY4QcNA5owATC', 'usuario'),
  ('Luis', 'luis@tareas.test', '$2y$12$1iDFNcISWOGtuL7l4/IiPeV4N.SKcTbhKtluu.OtrY4QcNA5owATC', 'usuario');

INSERT INTO tarea (titulo, descripcion, estado, id_usuario) VALUES
  ('Preparar la reunión del lunes', 'Hacer el orden del día y mandarlo a todos.', 'pendiente', 2),
  ('Revisar el informe de ventas', 'Comprobar que cuadran los números.', 'aceptada', 3),
  ('Actualizar la web', 'Cambiar los horarios de la página de contacto.', 'completada', 2);
