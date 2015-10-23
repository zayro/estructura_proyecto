<?php
include '../../../librerias/clase_consulta_bd.php';
$objeto = new consulta_bd();
$result = $objeto->json_bd("SELECT id, nombre from empresas");
print json_encode($result);
?>