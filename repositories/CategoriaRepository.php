<?php

require_once __DIR__ . '/../models/Categoria.php';

// Repositorio de categorías: es la única clase que habla con la tabla "categoria".
class CategoriaRepository{
    private mysqli $db;

    public function __construct(mysqli $db){
        $this->db = $db;
    }

    // Devuelve todas las categorías (para el desplegable de "Categoría").
    public function findAll(){
        $resultado = $this->db->query("SELECT id_categoria, nombre FROM categoria ORDER BY nombre");
        $categorias = array();
        while ($fila = $resultado->fetch_assoc()) {
            $categorias[] = new Categoria($fila['id_categoria'], $fila['nombre']);
        }
        return $categorias;
    }

    public function findByNombre($nombre){
        $stmt = $this->db->prepare("SELECT id_categoria, nombre FROM categoria WHERE nombre = ?");
        $stmt->bind_param("s", $nombre);
        $stmt->execute();
        $fila = $stmt->get_result()->fetch_assoc();

        if (!$fila) {
            return null;
        }
        return new Categoria($fila['id_categoria'], $fila['nombre']);
    }

    public function save(Categoria $categoria){
        $stmt = $this->db->prepare("INSERT INTO categoria (nombre) VALUES (?)");
        $nombre = $categoria->getNombre();
        $stmt->bind_param("s", $nombre);
        $stmt->execute();
        $categoria->setId($this->db->insert_id);
    }
}
