<?php

// Clase que representa una tarea.
class Tarea{
    private $id;
    private $titulo;
    private $descripcion;
    // 'pendiente', 'aceptada' o 'completada'
    private $estado;
    // id del usuario que tiene asignada la tarea
    private $idUsuario;
    // Nombre del usuario asignado, para enseñarlo en la lista
    private $nombreUsuario;
    private $idCategoria;
    private $nombreCategoria;

    public function __construct($id, $titulo, $descripcion, $estado, $idUsuario, $nombreUsuario, $idCategoria, $nombreCategoria){
        $this->id = $id;
        $this->titulo = $titulo;
        $this->descripcion = $descripcion;
        $this->estado = $estado;
        $this->idUsuario = $idUsuario;
        $this->nombreUsuario = $nombreUsuario;
        $this->idCategoria = $idCategoria;
        $this->nombreCategoria = $nombreCategoria;
    }

    public function getId(){
        return $this->id;
    }
    public function getTitulo(){
        return $this->titulo;
    }
    public function getDescripcion(){
        return $this->descripcion;
    }
    public function getEstado(){
        return $this->estado;
    }
    public function getIdUsuario(){
        return $this->idUsuario;
    }
    public function getNombreUsuario(){
        return $this->nombreUsuario;
    }
    public function getIdCategoria(){
        return $this->idCategoria;
    }
    public function getNombreCategoria(){
        return $this->nombreCategoria;
    }
}
