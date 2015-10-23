<?php

include('../../librerias/clase_login.php');

$objeto = new login();
extract($_REQUEST);




switch ($_SERVER['REQUEST_METHOD']) {
  case 'POST' :
    print json_encode($objeto->usuario_nuevo($usuario, $clave, $correo, $grupo, $identificacion));
    break;

  case  'GET':
    #print json_encode($objeto->recuperar_clave($usuario));
    print json_encode($objeto->validar_usuario("zayro"));
    break;

  case 'PUT':

    $objeto->setCabecera(200);
    parse_str(file_get_contents('php://input'), $arguments);
    
    function limpiarEntrada($data) {
      
    $entrada = array();
    if (is_array($data)) {
      foreach ($data as $key => $value) {
        $entrada[$key] = limpiarEntrada($value);
      }
    } else {
      if (get_magic_quotes_gpc()) {
        //Quitamos las barras de un string con comillas escapadas  
        //Aunque actualmente se desaconseja su uso, muchos servidores tienen activada la extensión magic_quotes_gpc.   
        //Cuando esta extensión está activada, PHP añade automáticamente caracteres de escape (\) delante de las comillas que se escriban en un campo de formulario.   
        $data = trim(stripslashes($data));
      }
      //eliminamos etiquetas html y php  
      $data = strip_tags($data);
      //Conviertimos todos los caracteres aplicables a entidades HTML  
      $data = htmlentities($data);
      $entrada = trim($data);
    }
    return $entrada;
  }
  
  $valor =limpiarEntrada($arguments) ;
    print json_encode($valor);
    #print json_encode($objeto->desactivar_usuario("zayro"));
    break;

  case 'DELETE':
    print json_encode($objeto->eliminar_usuario("zayro"));
    break;
}




  