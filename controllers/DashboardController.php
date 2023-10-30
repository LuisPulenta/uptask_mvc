<?php

namespace Controllers;

use MVC\Router;
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

public static function perfil(Router $router){

    session_start();

    isAuth();

    $router->render('dashboard/perfil',[
        'titulo'=>'Perfil',
    ]);
}

}