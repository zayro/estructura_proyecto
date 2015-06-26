<?php 

extract($_REQUEST);

include '../../../librerias/clase_procesos_bd.php';

$objeto = new procesos_bd();
$objeto->conectar_billar();


$sql =
"
DELETE FROM consumo 
WHERE
id = '$id'
;

";


$ejecutar = $objeto->alterar_bd_seguro($sql, "se elimino un consumo");

echo json_encode($ejecutar);


?>