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

    public function __construct($id, $titulo, $descripcion, $estado, $idUsuario, $nombreUsuario){
        $this->id = $id;
        $this->titulo = $titulo;
        $this->descripcion = $descripcion;
        $this->estado = $estado;
        $this->idUsuario = $idUsuario;
        $this->nombreUsuario = $nombreUsuario;
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
}
