<?php

require_once __DIR__ . '/../models/Tarea.php';

// Repositorio de tareas: es la única clase que habla con la tabla "tarea".
class TareaRepository{
    private mysqli $db;

    public function __construct(mysqli $db){
        $this->db = $db;
    }

    // Devuelve el ORDER BY según la columna elegida en la tabla.
    // No se puede usar ? para el ORDER BY, así que solo se aceptan estos valores escritos aquí;
    // cualquier otra cosa que llegue por la URL deja el orden normal (las más nuevas primero).
    private function ordenarPor($orden){
        if ($orden == 'tarea') {
            return "ORDER BY t.titulo, t.id_tarea DESC";
        } elseif ($orden == 'estado') {
            // estado es un ENUM, así que se ordena como está definido: pendiente, aceptada, completada
            return "ORDER BY t.estado, t.id_tarea DESC";
        } elseif ($orden == 'usuario') {
            return "ORDER BY u.nombre, t.id_tarea DESC";
        } elseif ($orden == 'categoria') {
            // Las que no tienen categoría (NULL) al final
            return "ORDER BY c.nombre IS NULL, c.nombre, t.id_tarea DESC";
        }
        return "ORDER BY t.id_tarea DESC";
    }

    // Todas las tareas (solo las ve el admin). El JOIN es para sacar el nombre del usuario.
    public function findAll($orden = null){
        $resultado = $this->db->query("SELECT t.id_tarea, t.titulo, t.descripcion, t.estado, t.id_usuario, u.nombre, c.id_categoria, c.nombre AS nombre_categoria
            FROM tarea t JOIN usuario u ON u.id_usuario = t.id_usuario LEFT JOIN categoria c ON c.id_categoria = t.id_categoria
            " . $this->ordenarPor($orden));
        $tareas = array();
        while ($fila = $resultado->fetch_assoc()) {
            $tareas[] = new Tarea($fila['id_tarea'], $fila['titulo'], $fila['descripcion'], $fila['estado'], $fila['id_usuario'], $fila['nombre'], $fila['id_categoria'], $fila['nombre_categoria']);
        }
        return $tareas;
    }

    // Las tareas asignadas a un usuario.
    public function findByUsuario($idUsuario, $orden = null){
        $stmt = $this->db->prepare("SELECT t.id_tarea, t.titulo, t.descripcion, t.estado, t.id_usuario, u.nombre, c.id_categoria, c.nombre AS nombre_categoria
            FROM tarea t JOIN usuario u ON u.id_usuario = t.id_usuario LEFT JOIN categoria c ON c.id_categoria = t.id_categoria
            WHERE t.id_usuario = ?
            " . $this->ordenarPor($orden));
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $tareas = array();
        while ($fila = $resultado->fetch_assoc()) {
            $tareas[] = new Tarea($fila['id_tarea'], $fila['titulo'], $fila['descripcion'], $fila['estado'], $fila['id_usuario'], $fila['nombre'], $fila['id_categoria'], $fila['nombre_categoria']);
        }
        return $tareas;
    }

    // Una tarea por su id, o null si no existe.
    public function findById($id){
        $stmt = $this->db->prepare("SELECT t.id_tarea, t.titulo, t.descripcion, t.estado, t.id_usuario, u.nombre, c.id_categoria, c.nombre AS nombre_categoria
            FROM tarea t JOIN usuario u ON u.id_usuario = t.id_usuario LEFT JOIN categoria c ON c.id_categoria = t.id_categoria
            WHERE t.id_tarea = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $fila = $stmt->get_result()->fetch_assoc();

        if (!$fila) {
            return null;
        }
        return new Tarea($fila['id_tarea'], $fila['titulo'], $fila['descripcion'], $fila['estado'], $fila['id_usuario'], $fila['nombre'], $fila['id_categoria'], $fila['nombre_categoria']);
    }

    // Guarda una tarea nueva. Empieza como 'pendiente' (lo pone la base de datos).
    public function save(Tarea $tarea){
        $stmt = $this->db->prepare("INSERT INTO tarea (titulo, descripcion, id_usuario, id_categoria) VALUES (?, ?, ?, ?)");
        $titulo = $tarea->getTitulo();
        $descripcion = $tarea->getDescripcion();
        $idUsuario = $tarea->getIdUsuario();
        $idCategoria = $tarea->getIdCategoria();
        $stmt->bind_param("ssii", $titulo, $descripcion, $idUsuario, $idCategoria);
        $stmt->execute();
    }

    // Guarda los cambios de una tarea que ya existe.
    public function update(Tarea $tarea){
        $stmt = $this->db->prepare("UPDATE tarea SET titulo = ?, descripcion = ?, estado = ?, id_usuario = ?, id_categoria = ? WHERE id_tarea = ?");
        $titulo = $tarea->getTitulo();
        $descripcion = $tarea->getDescripcion();
        $estado = $tarea->getEstado();
        $idUsuario = $tarea->getIdUsuario();
        $idCategoria = $tarea->getIdCategoria();
        $id = $tarea->getId();
        $stmt->bind_param("sssiii", $titulo, $descripcion, $estado, $idUsuario, $idCategoria, $id);
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
