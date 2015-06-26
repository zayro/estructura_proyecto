<?php
include '../../../librerias/clase_consulta_bd.php';
$objeto = new consulta_bd();
$objeto->conectar_billar();
$result = $objeto->json_bd("SELECT id, nombre from ubicacion");
echo json_encode($result);
?>