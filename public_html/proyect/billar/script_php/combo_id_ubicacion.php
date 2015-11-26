<?php

include 'cabecera_busqueda.php';
$objeto = new consulta_bd();
$objeto->conectar_billar($_SESSION['empresa']);
$result = $objeto->json_bd("SELECT id, nombre from ubicacion");
echo json_encode($result);
?>