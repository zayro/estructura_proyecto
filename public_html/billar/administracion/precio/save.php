<?php 
extract($_REQUEST);

include '../../../libreria_php/clase.php';
$conexion = new clase();
$conexion->local();
$conexion->conectar();


 $sql = "INSERT INTO PRECIO (nombre, hora, minuto) VALUES ('$nombre', '$hora', '$minuto')";


$ejecutar = $conexion->mysqli->query($sql);


if($ejecutar){
echo json_encode(array(
'success'=>true));

/*
$conexion->conectar_bdd_auditoria();
$conexion->auditoria($sql);
$conexion->auditoria_usuarios("se inserto campo de la tabla servicio  ");
*/

} else {
	
printf("Error: %s\n", $conexion->mysqli->error);	
die('No se pudo conectar: ' . $conexion->mysqli->connect_error);
echo "ocurrio un error";
	
	}


?>