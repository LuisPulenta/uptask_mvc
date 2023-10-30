<?php

namespace Controllers;

use MVC\Router;
use Model\Tarea;

class TareaController{

    public static function index(){

    }
    
    //----------------------------------------------------------------------------------------
    public static function crear(){
       
        if($_SERVER['REQUEST_METHOD']==='POST'){

            echo json_encode ($_POST);

            // session_start();

            // $proyectoId = $_POST['proyectoId'];

            // $proyecto = Proyecto::where('url',$proyectoId);

            // // if(!$proyecto){
            // //     $respuesta=[
            // //         'tipo' => 'error',
            // //         'mensaje' => 'Hubo un error al agregar la Tarea'
            // //     ];
                
            // //     echo json_encode($respuesta);
            // //     return;
            // // }
            
            // echo json_encode($proyecto);
        }
    }

    //----------------------------------------------------------------------------------------
    public static function actualizar(){
        if($_SERVER['REQUEST_METHOD']==='POST'){
            
        }
    }

    //----------------------------------------------------------------------------------------
    public static function eliminar(){
        if($_SERVER['REQUEST_METHOD']==='POST'){
            
        }
    }
}



