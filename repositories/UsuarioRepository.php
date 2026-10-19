<?php

require_once __DIR__ . '/../models/Usuario.php';
class UsuarioRepository{
    private mysqli $db;

    public function __construct(mysqli $db){
        $this->db = $db;
    }

    public function findByEmail(string $email): ?Usuario{
        $stmt = $this->db->prepare("SELECT id_usuario, nombre, apellido, email, contrasena, rol FROM usuario WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $fila = $stmt->get_result()->fetch_assoc();

        if (!$fila) {
            return null;
        }
        return new Usuario($fila['id_usuario'], $fila['nombre'], $fila['apellido'], $fila['email'], $fila['contrasena'], $fila['rol']);
    }

    public function findById(int $id): ?Usuario{
        $stmt = $this->db->prepare("SELECT id_usuario, nombre, apellido, email, contrasena, rol FROM usuario WHERE id_usuario = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $fila = $stmt->get_result()->fetch_assoc();

        if (!$fila) {
            return null;
        }
        return new Usuario($fila['id_usuario'], $fila['nombre'], $fila['apellido'], $fila['email'], $fila['contrasena'], $fila['rol']);
    }

    public function findAll(): array{
        $resultado = $this->db->query("SELECT id_usuario, nombre, apellido, email, contrasena, rol FROM usuario ORDER BY nombre");
        $usuarios = [];
        while ($fila = $resultado->fetch_assoc()) {
            $usuarios[] = new Usuario($fila['id_usuario'], $fila['nombre'], $fila['apellido'], $fila['email'], $fila['contrasena'], $fila['rol']);
        }
        return $usuarios;
    }

    public function save(Usuario $usuario){
        $stmt = $this->db->prepare("INSERT INTO usuario (nombre, apellido, email, contrasena) VALUES (?, ?, ?, ?)");
        $nombre = $usuario->getNombre();
        $apellido = $usuario->getApellido();
        $email = $usuario->getEmail();
        $contrasena = $usuario->getPassword();
        $stmt->bind_param("ssss", $nombre, $apellido, $email, $contrasena);
        $stmt->execute();
    }

    public function cambiarRol(int $id, string $rol){
        $stmt = $this->db->prepare("UPDATE usuario SET rol = ? WHERE id_usuario = ?");
        $stmt->bind_param("si", $rol, $id);
        $stmt->execute();
    }

    public function delete(int $id){
        $stmt = $this->db->prepare("DELETE FROM usuario WHERE id_usuario = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }
}
