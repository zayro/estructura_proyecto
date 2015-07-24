<?php

require('../../../librerias/clase_consulta_bd.php');

$objeto = new consulta_bd();

if (extract($_REQUEST)) {

  $sql = sprintf("SELECT count(*) as encontrado, u.usuario, u.grupo, g.nombre as nombre_grupo, u.identificacion, u.imagen, u.empresa as codigo_empresa, e.nombre as empresa 
  FROM usuarios as u join grupo as g on u.grupo  = g.id join empresas as e on e.id = u.empresa  
  WHERE u.usuario = '%s' and u.clave = encode( '%s' , 'clave') and u.empresa = '%s' and estado = '1';", $objeto->real_escape_string($usuario), $objeto->real_escape_string($clave), $objeto->real_escape_string($empresa));

  $verificar = array();

  $verificar = $objeto->consulta_json($sql);

  if ($verificar->encontrado != 0) {

    $verificar->success = true;
    $_SESSION['usuario'] = $verificar->usuario;
    $_SESSION['grupo'] = $verificar->grupo;
    $_SESSION['nombre_grupo'] = $verificar->nombre_grupo;
    $_SESSION['identificacion'] = $verificar->identificacion;
    $_SESSION['codigo_empresa'] = $verificar->codigo_empresa;
    $_SESSION['empresa'] = $verificar->empresa;
    $_SESSION['imagen'] = $verificar->imagen;
    
  } else {

    $verificar->success = false;
  }

  echo json_encode($verificar);
}
?>