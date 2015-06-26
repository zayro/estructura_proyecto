<?php 

extract($_REQUEST);

include '../../../librerias/clase_procesos_bd.php';

$objeto = new procesos_bd();
$objeto->conectar_billar();


$sql = "INSERT into consumo (id_servicio, cantidad, id_tiempo) values ('$id_servicio', '$cantidad_consumo', '$id_tiempo')";
$mensaje = "se almanceno un consumo";

$datos = $objeto->alterar_bd_seguro($sql, $mensaje);


echo json_encode($datos);
?>