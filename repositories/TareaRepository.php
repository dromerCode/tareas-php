<?php

require_once __DIR__ . '/../models/Tarea.php';
class TareaRepository{
    private mysqli $db;

    // SELECT común de las listas: la tarea con el nombre de quién la creó y a quién está asignada
    private string $selectLista = "SELECT t.id_tarea, t.titulo, t.descripcion, t.estado, t.fecha_limite, t.id_creador, t.id_asignado,
                c.nombre AS nombre_creador, a.nombre AS nombre_asignado
            FROM tarea t
            JOIN usuario c ON c.id_usuario = t.id_creador
            LEFT JOIN usuario a ON a.id_usuario = t.id_asignado";

    public function __construct(mysqli $db){
        $this->db = $db;
    }

    public function findById(int $id): ?Tarea{
        $stmt = $this->db->prepare("SELECT id_tarea, titulo, descripcion, estado, fecha_limite, id_creador, id_asignado FROM tarea WHERE id_tarea = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $fila = $stmt->get_result()->fetch_assoc();

        if (!$fila) {
            return null;
        }
        return new Tarea($fila['id_tarea'], $fila['titulo'], $fila['descripcion'], $fila['estado'], $fila['fecha_limite'], $fila['id_creador'], $fila['id_asignado']);
    }

    // Las listas devuelven arrays (no objetos Tarea) porque llevan también los nombres
    public function findAsignadasA(int $idUsuario): array{
        $stmt = $this->db->prepare($this->selectLista . " WHERE t.id_asignado = ? ORDER BY t.estado, t.fecha_limite");
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function findCreadasPor(int $idUsuario): array{
        $stmt = $this->db->prepare($this->selectLista . " WHERE t.id_creador = ? ORDER BY t.estado, t.fecha_limite");
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function findSinAsignar(): array{
        $resultado = $this->db->query($this->selectLista . " WHERE t.id_asignado IS NULL ORDER BY t.fecha_limite");
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    public function findAll(): array{
        $resultado = $this->db->query($this->selectLista . " ORDER BY t.estado, t.fecha_limite");
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    public function save(Tarea $tarea){
        $stmt = $this->db->prepare("INSERT INTO tarea (titulo, descripcion, fecha_limite, id_creador, id_asignado) VALUES (?, ?, ?, ?, ?)");
        $titulo = $tarea->getTitulo();
        $descripcion = $tarea->getDescripcion();
        $fecha = $tarea->getFechaLimite();
        $creador = $tarea->getIdCreador();
        $asignado = $tarea->getIdAsignado();
        $stmt->bind_param("sssii", $titulo, $descripcion, $fecha, $creador, $asignado);
        $stmt->execute();
    }

    public function update(Tarea $tarea){
        $stmt = $this->db->prepare("UPDATE tarea SET titulo = ?, descripcion = ?, estado = ?, fecha_limite = ?, id_asignado = ? WHERE id_tarea = ?");
        $titulo = $tarea->getTitulo();
        $descripcion = $tarea->getDescripcion();
        $estado = $tarea->getEstado();
        $fecha = $tarea->getFechaLimite();
        $asignado = $tarea->getIdAsignado();
        $id = $tarea->getId();
        $stmt->bind_param("ssssii", $titulo, $descripcion, $estado, $fecha, $asignado, $id);
        $stmt->execute();
    }

    // Aceptar: la tarea pasa a "aceptada" y se queda asignada a quien la acepta
    public function aceptar(int $idTarea, int $idUsuario){
        $stmt = $this->db->prepare("UPDATE tarea SET estado = 'aceptada', id_asignado = ? WHERE id_tarea = ?");
        $stmt->bind_param("ii", $idUsuario, $idTarea);
        $stmt->execute();
    }

    public function completar(int $idTarea){
        $stmt = $this->db->prepare("UPDATE tarea SET estado = 'completada' WHERE id_tarea = ?");
        $stmt->bind_param("i", $idTarea);
        $stmt->execute();
    }

    public function delete(int $id){
        $stmt = $this->db->prepare("DELETE FROM tarea WHERE id_tarea = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }
}
