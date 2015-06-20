<?php 
/**
 * EJEMPLOS Y PRUEBAS
 * 


# MODULO DE PRUEBAS DE CONSULTAS
include '../librerias/clase_consulta_bd.php';

$objeto_consulta = new consulta_bd();
$ip_cliente = $objeto_consulta->obtener_ip();

$var = $objeto_consulta->json_bd("SELECT * FROM menu");
$objeto_consulta->imprime_json($var);


 # MODULO DE PRUEBAS DE LOG


include 'librerias/clase_log.php';
$objeto_log = new log();
$objeto_log->escribir_log("RESUMEN INFORMATIVO $ip_cliente ", "INFORMATIVO");

 */

include '../librerias/clase_procesos_bd.php';

$objeto_proceso = new procesos_bd();

#$sql = "INSERT INTO `menu` (`nombre`) VALUES ('PRUEBA')";
$sql = "CALL `crear usuarios` ('loco', 'loco', 'gato', '102030');";

$mensaje = "PRUEBAS DE AUDITORIA";

#echo  json_encode($objeto_proceso->alterar_bd($sql,$mensaje));

echo  json_encode($objeto_proceso->procedimiento_almacenado($sql));



?>