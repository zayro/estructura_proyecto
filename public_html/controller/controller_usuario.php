<?php

include('../model/model_usuario.php');

$objeto = new usuario();

switch ($_SERVER['REQUEST_METHOD']) {
  
  case 'POST' :
    
    extract($_REQUEST);
    
    print json_encode($objeto->usuario_nuevo($usuario, $clave, $correo, $grupo, $identificacion));
    
    break;

  case  'GET':
    #print json_encode($objeto->recuperar_clave($usuario));
    extract($_REQUEST);
    
    print json_encode($objeto->validar_usuario("zayro"));
    
    break;

  case 'PUT':
    
    $datos = file_get_contents("php://input");
    
    parse_str($datos);
    
    $objeto->setCabecera(200);
  
    print json_encode($objeto->desactivar_usuario($identificacion));
    
    break;

  case 'DELETE':
    
    $datos = file_get_contents("php://input");
    
    parse_str($datos);
    
    print json_encode($objeto->eliminar_usuario($identificacion));
    
    break;
  
}