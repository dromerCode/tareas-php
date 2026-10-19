<?php

require_once __DIR__ . '/../models/Usuario.php';

// Repositorio de usuarios: es la única clase que habla con la tabla "usuario".
class UsuarioRepository{
    private mysqli $db;

    public function __construct(mysqli $db){
        $this->db = $db;
    }

    // Busca un usuario por su email. Devuelve el Usuario o null si no existe.
    public function findByEmail($email){
        $stmt = $this->db->prepare("SELECT id_usuario, nombre, email, contrasena, rol FROM usuario WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $fila = $stmt->get_result()->fetch_assoc();

        if (!$fila) {
            return null;
        }
        return new Usuario($fila['id_usuario'], $fila['nombre'], $fila['email'], $fila['contrasena'], $fila['rol']);
    }

    // Devuelve todos los usuarios (para el desplegable de "Asignar a").
    public function findAll(){
        $resultado = $this->db->query("SELECT id_usuario, nombre, email, contrasena, rol FROM usuario ORDER BY nombre");
        $usuarios = array();
        while ($fila = $resultado->fetch_assoc()) {
            $usuarios[] = new Usuario($fila['id_usuario'], $fila['nombre'], $fila['email'], $fila['contrasena'], $fila['rol']);
        }
        return $usuarios;
    }

    // Guarda un usuario nuevo. El id lo pone la base de datos y el rol es 'usuario' por defecto.
    public function save(Usuario $usuario){
        $stmt = $this->db->prepare("INSERT INTO usuario (nombre, email, contrasena) VALUES (?, ?, ?)");
        $nombre = $usuario->getNombre();
        $email = $usuario->getEmail();
        $password = $usuario->getPassword();
        $stmt->bind_param("sss", $nombre, $email, $password);
        $stmt->execute();
    }
}
