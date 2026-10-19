<?php
class Tarea{
    private $id_tarea;
    private string $titulo;
    private $descripcion;
    private string $estado;
    private $fecha_limite;
    private $id_creador;
    private $id_asignado;

    public function __construct($id, string $titulo, $descripcion, string $estado, $fecha_limite, $id_creador, $id_asignado){
        $this->id_tarea = $id;
        $this->titulo = $titulo;
        $this->descripcion = $descripcion;
        $this->estado = $estado;
        $this->fecha_limite = $fecha_limite;
        $this->id_creador = $id_creador;
        $this->id_asignado = $id_asignado;
    }

    public function getId(){
        return $this->id_tarea;
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
    public function getFechaLimite(){
        return $this->fecha_limite;
    }
    public function getIdCreador(){
        return $this->id_creador;
    }
    public function getIdAsignado(){
        return $this->id_asignado;
    }
}
