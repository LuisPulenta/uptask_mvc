<?php

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController{

    //----------------------- login -----------------------------
    public static function login(Router $router){
         //Alertas vacías
        $alertas = [];
        $auth = new Usuario; 

        if($_SERVER['REQUEST_METHOD']==='POST'){

            $auth = new Usuario($_POST); 
            $alertas = $auth->validarLogin();

            if(empty($alertas)){
                //Comprobar que exista el Usuario
                $usuario = Usuario::where('email',$auth->email);


                if(!$usuario || !$usuario->confirmado){
                    Usuario::setAlerta('error', 'Usuario no existe o no está confirmado');
                }
                else{
                    if(password_verify($_POST['password'],$usuario->password)){
                        session_start();
                        $_SESSION['id']=$usuario->id;
                        $_SESSION['nombre']=$usuario->nombre." ".$usuario->apellido;
                        $_SESSION['email']=$usuario->email;
                        $_SESSION['login']=true;

                        //Redireccionamiento
                        header('Location: /dashboard');
                     
                    }else{
                        Usuario::setAlerta('error', 'Password incorrecto');
                    };
                }
              }
        }
        //Render a la vista
        $alertas=Usuario::getAlertas();
        $router->render('auth/login',[
            'titulo'=>'Iniciar Sesión',
            'alertas' =>$alertas
        ]);
    }

    //----------------------- logout -----------------------------
    public static function logout(){
        session_start()     ;
        $_SESSION=[];
        header('Location: /');
    }

    //----------------------- crear -----------------------------
    public static function crear(Router $router){
        
        $usuario = new Usuario(); 

        //Alertas vacías
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] ==='POST' ) {
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();
            $existeUsuario = Usuario::where('email',$usuario->email);

            if(empty($alertas)){
                if($existeUsuario) {
                    Usuario::setAlerta('error','El Usuario ya está registrado');
                    $alertas = Usuario::getAlertas();
                }else{
                    

                        //Hashear el Password
                        $usuario->hashPassword();
                    
                        //Eliminar Password2
                        unset($usuario->password2);

                        //Generar Token
                        $usuario->crearToken();

                        //Crear Nuevo Usuario
                        $resultado=$usuario->guardar();

                        //Enviar el email
                        $email = new Email( $usuario->email,$usuario->nombre,$usuario->token);
                        $email->enviarConfirmacion();
                        
                        if($resultado){
                            header('Location: /mensaje');
                        }
                }
            }            
        }

        //Render a la vista
        $router->render('auth/crear',[
            'titulo'=>'Crear Cuenta',
            'usuario'=>$usuario,
            'alertas'=>$alertas,
        ]);
    }
    
    //----------------------- olvide -----------------------------
    public static function olvide(Router $router){
        
        //Alertas vacías
        $alertas = [];

        if($_SERVER['REQUEST_METHOD']==='POST'){
            $usuario = new Usuario($_POST);
            $alertas = $usuario->validarEmail();

            if(empty($alertas)){

                //Comprobar que exista el Usuario
                $usuario = Usuario::where('email',$usuario->email);
                
                if($usuario && $usuario->confirmado==="1"){

                //Generar Token único
                $usuario->crearToken();
                unset($usuario->password2);
                $usuario->guardar();
      
                //Enviar el email
                $email = new Email( $usuario->email,$usuario->nombre,$usuario->token);
                $email->enviarInstrucciones();
      
                Usuario::setAlerta('exito', 'Hemos enviado las instrucciones a tu email');
                }else{
                  Usuario::setAlerta('error', 'El Usuario no existe o no está confirmado');
                }
              }
        }
        //Render a la vista
        $alertas = Usuario::getAlertas();
        $router->render('auth/olvide',[
            'titulo'=>'Olvidé mi Password',
            'alertas' =>$alertas    
        ]);
    }

    //----------------------- reestablecer -----------------------------
    public static function reestablecer(Router $router){
        
        $token=s($_GET['token']);
        $mostrar=true;

        if(!$token){
            header('Location:/');
        }

        $alertas=[];
        
        $usuario = Usuario::where('token',$token);

        if(empty($usuario)){
            //Mostrar mensaje de error
            Usuario::setAlerta('error','Token no válido');
            $mostrar=false;
        }

        if($_SERVER['REQUEST_METHOD']==='POST'){

            //Leer el nuevo Password y guardarlo

            $password = new Usuario($_POST); 
            $alertas = $password->validarPassword();
            if(empty($alertas)){
                $usuario->password=null;
                $usuario->password=$password->password;
                $usuario->hashPassword();
                $usuario->token = null;
                unset($usuario->password2);
                $resultado = $usuario->guardar();
                if($resultado){
                    header('Location:/');
                }
            }
        
        }
        $alertas = Usuario::getAlertas();
         //Render a la vista
         $router->render('auth/reestablecer',[
            'titulo'=>'Reestablecer Password',
            'alertas'=> $alertas,
            'mostrar'=> $mostrar
        ]);
    }

    //----------------------- mensaje -----------------------------
    public static function mensaje(Router $router){
        //Render a la vista
        $router->render('auth/mensaje',[
            'titulo'=>'Cuenta creada exitosamente'
        ]);      
    }

    //--------------------------------------------------------------------------------------
    public static function confirmar(Router $router){
        $alertas=[];

        $token=s($_GET['token']);

        if(!$token) header('Location:/');

        $usuario = Usuario::where('token',$token);

        if(empty($usuario)){
        //Mostrar mensaje de error
        Usuario::setAlerta('error','Token no válido');
        }else{
        //Modificar usuario confirmado
        $usuario->confirmado = 1;
        unset($usuario->password2);
        $usuario->token = '';
        $usuario->guardar();
        Usuario::setAlerta('exito','Cuenta comprobada correctamente');
        
        }
        
        //Obtener alertas
        $alertas=Usuario::getAlertas();

        //Renderizar la vista
        $router->render('auth/confirmar',[
            'titulo'=>'Confirma tu Cuenta en UpTasck',
            'alertas' => $alertas
        ]);
    }























}
?>