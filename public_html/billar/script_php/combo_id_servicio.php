<?php
include '../../../librerias/clase_consulta_bd.php';
$objeto = new consulta_bd();
$objeto->conectar_billar($_SESSION['empresa']);
$result = $objeto->json_bd("SELECT id, nombre, precio from servicio");
echo json_encode($result);
?>