<?php 
extract($_REQUEST);

include 'cabecera_edicion.php';

$objeto = new procesos_bd();
$objeto->conectar_billar($_SESSION['empresa']);



$sql = "CALL guardar_mesa ('$id_ubicacion')";

$ejecutar = $objeto->procedimiento_almacenado($sql, "se habilita una mesa");


echo json_encode($ejecutar);
?>