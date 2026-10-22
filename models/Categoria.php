<?php

// Clase que representa una categoría de la aplicación.
class Categoria{
    private $id;
    private $nombre;

    public function __construct($id, $nombre){
        $this->id = $id;
        $this->nombre = $nombre;
    }

    public function getId(){
        return $this->id;
    }
    public function getNombre(){
        return $this->nombre;
    }
    // Lo usa el repositorio al guardar, para poner el id que ha dado la base de datos
    public function setId($id){
        $this->id = $id;
    }
}