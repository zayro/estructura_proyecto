<?php 

extract($_REQUEST);

include 'cabecera_edicion.php';

$objeto = new procesos_bd();
$objeto->conectar_billar($_SESSION['empresa']);


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