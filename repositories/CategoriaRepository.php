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
}