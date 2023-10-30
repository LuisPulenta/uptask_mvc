<?php

namespace Model;

class Usuario extends ActiveRecord{
    //Base de Datos
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id','nombre','email','password','token','confirmado'];

    public $id;
    public $nombre;
    public $email;
    public $password;
    public $password2;
    public $token;
    public $confirmado;


    public function __construct($args = []){
        $this-> id = $args['id'] ?? null;
        $this-> nombre = $args['nombre'] ?? '';
        $this-> email = $args['email'] ?? '';
        $this-> password = $args['password'] ?? '';
        $this-> password2 = $args['password2'] ?? '';
        $this-> token = $args['token'] ?? '';
        $this-> confirmado = $args['confirmado'] ?? 0;
    }

     //Mensajes de validación para la creación de una cuenta

     public function validarNuevaCuenta(){
        if(!$this->nombre){
            self::$alertas['error'][]='El Nombre es obligatorio';
        }
        if(!$this->email){
            self::$alertas['error'][]='El Email es obligatorio';
        }
        if(!$this->password){
            self::$alertas['error'][]='El Password es obligatorio';
        }
        if(strlen($this->password)<6){
            self::$alertas['error'][]='El Password debe tener al menos 6 caracteres';
        }
        if($this->password !== $this->password2){
            self::$alertas['error'][]='Los Passwords son diferentes';
        }

        return self::$alertas;
    }

    //------------------------------------------------------------------------
    //Revisa si el usuario ya existe a travès del email
    
    public function validarEmail() {
        if(!$this->email) {
            self::$alertas['error'][] = 'El Email es Obligatorio';
        }
        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = 'Email no válido';
        }
        return self::$alertas;
    }

    //------------------------------------------------------------------------
    //Hashear el Password
    public function hashPassword(){
    $this->password = password_hash($this->password,PASSWORD_BCRYPT);
    }

    //------------------------------------------------------------------------
    //Generar Token
    public function crearToken(){
        $this->token = md5(uniqid());
    }

    //------------------------------------------------------------------------
    //Mensajes de validación de Password
    public function validarPassword(){
        if(!$this->password){
            self::$alertas['error'][]='El Password es obligatorio';
        }
        if(strlen($this->password)<6){
            self::$alertas['error'][]='El Password debe tener al menos 6 caracteres';
        }
        return self::$alertas;
    }

    //------------------------------------------------------------------------
    //Mensajes de validación para el Login
    public function validarLogin(){
        if(!$this->email){
            self::$alertas['error'][]='El Email es obligatorio';
        }
        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = 'Email no válido';
        }
        if(!$this->password){
            self::$alertas['error'][]='El Password es obligatorio';
        }
        return self::$alertas;
    }
}    
?>
