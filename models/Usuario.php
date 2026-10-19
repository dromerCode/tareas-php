<?php
class Usuario{
    private $id_usuario;
    private string $nombre;
    private string $apellido;
    private string $email;
    private string $password;
    private string $rol;

    public function __construct($id, string $nombre, string $apellido, string $email, string $password, string $rol = 'usuario'){
        $this->id_usuario = $id;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->email = $email;
        $this->password = $password;
        $this->rol = $rol;
    }

    public function getIdUsuario(){
        return $this->id_usuario;
    }
    public function getNombre(){
        return $this->nombre;
    }
    public function getApellido(){
        return $this->apellido;
    }
    public function getEmail(){
        return $this->email;
    }
    public function getPassword(){
        return $this->password;
    }
    public function getRol(){
        return $this->rol;
    }
}
