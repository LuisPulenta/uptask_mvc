<?php

namespace Controllers;

use MVC\Router;
use Model\Usuario;
use Model\Proyecto;

class DashboardController{

//----------------------- login -----------------------------

public static function index(Router $router){

    session_start();

    isAuth();

    $id=$_SESSION['id'];

    $proyectos = Proyecto::belongsTo('propietarioId',$id);
    
    $router->render('dashboard/index',[
        'titulo'=>'Proyectos',
        'proyectos'=>$proyectos
    ]);
}

//----------------------- crear_proyecto -----------------------------
public static function crear_proyecto(Router $router){

    session_start();

    isAuth();
    $alertas = [];

    if($_SERVER['REQUEST_METHOD']==='POST'){
            $proyecto=new Proyecto($_POST);

            //Validación
            $alertas = $proyecto->validarProyecto();

            if(empty($alertas)){
                //Guardar el Proyecto
                $proyecto->url=md5(uniqid());
                $proyecto->propietarioId=$_SESSION['id'];
                $proyecto->guardar();
                header('Location: /proyecto?id=' . $proyecto->url);
            }
            debuguear($proyecto);
    }

    $router->render('dashboard/crear-proyecto',[
        'titulo'=>'Crear Proyecto',
        'alertas' =>$alertas
    ]);
}

//----------------------- proyecto -----------------------------
public static function proyecto(Router $router){

    session_start();

    isAuth();

    //Revisar que la persona que visita el Proyecto es quien lo creó

    $token = $_GET['id'];
    if(!$token){
        header ('Location: /dashboard');
    }

    $proyecto = Proyecto::where('url', $token);

    if($proyecto->propietarioId !== $_SESSION['id']){
        header ('Location: /dashboard');
    };


    $alertas = [];

    if($_SERVER['REQUEST_METHOD']==='POST'){
            $proyecto=new Proyecto($_POST);

            //Validación
            $alertas = $proyecto->validarProyecto();

            if(empty($alertas)){
                //Guardar el Proyecto
                $proyecto->url=md5(uniqid());
                $proyecto->propietarioId=$_SESSION['id'];
                $proyecto->guardar();
                header('Location: /proyecto?id=' . $proyecto->url);
            }
            debuguear($proyecto);
    }

    $router->render('dashboard/proyecto',[
        'titulo'=>$proyecto->proyecto,
        'alertas' =>$alertas
    ]);
}

//----------------------- perfil -----------------------------
public static function perfil(Router $router){

    session_start();

    isAuth();

    $alertas = [];

    $usuario = Usuario::find($_SESSION['id']);

    if($_SERVER['REQUEST_METHOD']==='POST'){
        $usuario->sincronizar($_POST);
        $alertas = $usuario->validarPerfil();

        if(empty($alertas)){

            $existeUsuario = Usuario::where('email',$usuario->email);

            if($existeUsuario && $existeUsuario->id !==$usuario->id){
                //Mostrar mensaje de error
                Usuario::setAlerta('error','Email ya registrado');
                $alertas=$usuario->getAlertas();

            }else{
                //Guardar el Usuario
                $usuario->guardar();

                Usuario::setAlerta('exito','Guardado correctamente');
                $alertas=$usuario->getAlertas();
            }

            
            //Asignar el nombre a la barra
            $_SESSION['nombre']=$usuario->nombre;
        }

    };

    $router->render('dashboard/perfil',[
        'titulo'=>'Perfil',
        'alertas' => $alertas,
        'usuario' => $usuario
    ]);
}

//----------------------- cambiar_password -----------------------------
public static function cambiar_password(Router $router){

    session_start();

    isAuth();

    $alertas = [];

    $usuario = Usuario::find($_SESSION['id']);
    
    if($_SERVER['REQUEST_METHOD']==='POST'){

        $usuario->sincronizar($_POST);

        $alertas = $usuario->nuevo_password();

        if(empty($alertas)){

            $resultado = $usuario->comprobar_password();

            if(!$resultado){
                //Mostrar mensaje de error
                Usuario::setAlerta('error','Password actual incorrecto');
                $alertas=$usuario->getAlertas();

            }else{
                $usuario->password = $usuario->password_nuevo;

                unset($usuario->password_actual);
                unset($usuario->password_nuevo);

                $usuario->hashPassword();

                $resultado= $usuario->guardar();

                if($resultado){
                    Usuario::setAlerta('exito','Password actualizado correctamente');
                    $alertas=$usuario->getAlertas();
                };                
            }

            
            //Asignar el nombre a la barra
            $_SESSION['nombre']=$usuario->nombre;
        }

        
        
    };

    $router->render('dashboard/cambiar-password',[
        'titulo'=>'Cambiar Password',
        'alertas' => $alertas,
        'usuario' => $usuario
    ]);
}

}