<?php

 extract($_REQUEST);
 $datos = array();
 
include '../../../../librerias/clase_procesos_bd.php';

$objeto = new procesos_bd();
$objeto->conectar_parqueadero();


try{
  $objeto->inicia_transaccion();
  
  $sql = "INSERT INTO vehiculo (placa, tiempo_entrada, vehiculo ) VALUES (UPPER('$placa'), now(), '$vehiculo')";
  $mensaje  = "SE GUARDO VEHICULO";
  
   $datos = $objeto->transaccion($sql, $mensaje);
  

  
  
  $objeto->finaliza_transaccion();
  
}  catch (Exception $e) {
  
  $datos['suceso']= $e->getMessage();
  $datos['success']= false;
  $objeto->cancela_transaccion();
  
  
}

$objeto->imprime_json($datos);