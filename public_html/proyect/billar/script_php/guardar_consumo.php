<?php 

extract($_REQUEST);

include 'cabecera_edicion.php';

$objeto = new procesos_bd();
$objeto->usuario_online();
$objeto->conectar_billar($_SESSION['empresa']);


$sql = "INSERT into consumo (id_servicio, cantidad, id_tiempo) values ('$id_servicio', '$cantidad_consumo', '$id_tiempo')";
$mensaje = "se almanceno un consumo";

$datos = $objeto->alterar_bd_seguro($sql, $mensaje);


echo json_encode($datos);
?>