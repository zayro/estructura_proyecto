<?php include 'librerias/clase_procesos_bd.php';

$conexion = new procesos_bd();

$resultado[] = array();

$resultado['uno'] =  $conexion->json_bd("select usuario from usuarios");



$conexion->imprime_json($resultado);







?>