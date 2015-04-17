<?php 
/**
 * MODULO DE PRUEBAS DE CONSULTAS
 */

include 'librerias/clase_consulta_bd.php';

$objeto_consulta = new consulta_bd();
$ip_cliente = $objeto_consulta->obtener_ip();

$var = $objeto_consulta->json_bd("SELECT * FROM menu");
$objeto_consulta->imprime_json($var);

/**
 * MODULO DE PRUEBAS DE LOG
 */

include 'librerias/clase_log.php';
$objeto_log = new log();
$objeto_log->escribir_log("RESUMEN INFORMATIVO $ip_cliente ", "INFORMATIVO");



?>