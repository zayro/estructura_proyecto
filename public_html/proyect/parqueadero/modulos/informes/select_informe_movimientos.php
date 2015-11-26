<?php

include '../../../librerias/clase_consulta_bd.php';

$conexion = new consulta_bd();

$resultado = array();


$sql1 = "SELECT *  FROM informe_movimientos; ";
$sql2 = "select sum(valor) as total from informe_movimientos; ";

$resultado['registros'] = $conexion->json_bd($sql1);
$resultado['total'] = $conexion->json_bd($sql2);


echo json_encode($resultado);



