<?php

require('../../../librerias/clase_consulta_bd.php');

$objeto = new consulta_bd();

if (extract($_REQUEST)) {

  $sql = sprintf("SELECT count(*) as encontrado, usuario, grupo, g.nombre as nombre_grupo, identificacion, imagen 
  FROM usuarios as u join grupo as g on u.grupo  = g.id  
  WHERE usuario = '%s' and clave = encode( '%s' , 'clave') and estado = '1';", $objeto->real_escape_string($usuario), $objeto->real_escape_string($clave));

  $verificar = array();

  $verificar = $objeto->consulta_json($sql);

  if ($verificar->encontrado != 0) {

    $verificar->success = true;
    $_SESSION['usuario'] = $verificar->usuario;
    $_SESSION['grupo'] = $verificar->grupo;
    $_SESSION['nombre_grupo'] = $verificar->nombre_grupo;
    $_SESSION['identificacion'] = $verificar->identificacion;
    $_SESSION['imagen'] = $verificar->imagen;
  } else {

    $verificar->success = false;
  }

  echo json_encode($verificar);
}
?>