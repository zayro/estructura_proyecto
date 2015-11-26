<?php

include '../../../librerias/clase_consulta_bd.php';

$conexion = new consulta_bd();

$items = array();


/**
 * INCIA CICLO 1
 * CONSULTAMOS EL GRUPO
 */
$sql1 = "SELECT
*
FROM
informe_pendientes
 ";
$resultado = $conexion->consulta($sql1);

while ($row = $resultado->fetch_object()) {
	array_push($items, $row);
	}

/*
 * ######## cierre de ciclo 1
 */

$result["registros"] = $items;


echo json_encode($result);



