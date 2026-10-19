<?php

require_once __DIR__ . '/../models/Tarea.php';

// Repositorio de tareas: es la única clase que habla con la tabla "tarea".
class TareaRepository{
    private mysqli $db;

    public function __construct(mysqli $db){
        $this->db = $db;
    }

    // Todas las tareas (solo las ve el admin). El JOIN es para sacar el nombre del usuario.
    public function findAll(){
        $resultado = $this->db->query("SELECT t.id_tarea, t.titulo, t.descripcion, t.estado, t.id_usuario, u.nombre
            FROM tarea t JOIN usuario u ON u.id_usuario = t.id_usuario
            ORDER BY t.id_tarea DESC");
        $tareas = array();
        while ($fila = $resultado->fetch_assoc()) {
            $tareas[] = new Tarea($fila['id_tarea'], $fila['titulo'], $fila['descripcion'], $fila['estado'], $fila['id_usuario'], $fila['nombre']);
        }
        return $tareas;
    }

    // Las tareas asignadas a un usuario.
    public function findByUsuario($idUsuario){
        $stmt = $this->db->prepare("SELECT t.id_tarea, t.titulo, t.descripcion, t.estado, t.id_usuario, u.nombre
            FROM tarea t JOIN usuario u ON u.id_usuario = t.id_usuario
            WHERE t.id_usuario = ?
            ORDER BY t.id_tarea DESC");
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $tareas = array();
        while ($fila = $resultado->fetch_assoc()) {
            $tareas[] = new Tarea($fila['id_tarea'], $fila['titulo'], $fila['descripcion'], $fila['estado'], $fila['id_usuario'], $fila['nombre']);
        }
        return $tareas;
    }

    // Una tarea por su id, o null si no existe.
    public function findById($id){
        $stmt = $this->db->prepare("SELECT t.id_tarea, t.titulo, t.descripcion, t.estado, t.id_usuario, u.nombre
            FROM tarea t JOIN usuario u ON u.id_usuario = t.id_usuario
            WHERE t.id_tarea = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $fila = $stmt->get_result()->fetch_assoc();

        if (!$fila) {
            return null;
        }
        return new Tarea($fila['id_tarea'], $fila['titulo'], $fila['descripcion'], $fila['estado'], $fila['id_usuario'], $fila['nombre']);
    }

    // Guarda una tarea nueva. Empieza como 'pendiente' (lo pone la base de datos).
    public function save(Tarea $tarea){
        $stmt = $this->db->prepare("INSERT INTO tarea (titulo, descripcion, id_usuario) VALUES (?, ?, ?)");
        $titulo = $tarea->getTitulo();
        $descripcion = $tarea->getDescripcion();
        $idUsuario = $tarea->getIdUsuario();
        $stmt->bind_param("ssi", $titulo, $descripcion, $idUsuario);
        $stmt->execute();
    }

    // Guarda los cambios de una tarea que ya existe.
    public function update(Tarea $tarea){
        $stmt = $this->db->prepare("UPDATE tarea SET titulo = ?, descripcion = ?, estado = ?, id_usuario = ? WHERE id_tarea = ?");
        $titulo = $tarea->getTitulo();
        $descripcion = $tarea->getDescripcion();
        $estado = $tarea->getEstado();
        $idUsuario = $tarea->getIdUsuario();
        $id = $tarea->getId();
        $stmt->bind_param("sssii", $titulo, $descripcion, $estado, $idUsuario, $id);
        $stmt->execute();
    }

    // Cambia solo el estado (para aceptar y completar).
    public function cambiarEstado($id, $estado){
        $stmt = $this->db->prepare("UPDATE tarea SET estado = ? WHERE id_tarea = ?");
        $stmt->bind_param("si", $estado, $id);
        $stmt->execute();
    }

    public function delete($id){
        $stmt = $this->db->prepare("DELETE FROM tarea WHERE id_tarea = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }
}
