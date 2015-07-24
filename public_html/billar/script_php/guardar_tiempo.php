<?php 
extract($_REQUEST);

include '../../../librerias/clase_procesos_bd.php';

$objeto = new procesos_bd();
$objeto->conectar_billar($_SESSION['empresa']);



$sql = "CALL guardar_mesa ('$id_ubicacion')";

$ejecutar = $objeto->procedimiento_almacenado($sql, "se habilita una mesa");


echo json_encode($ejecutar);
?>