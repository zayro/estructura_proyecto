<?php 
extract($_REQUEST);

include '../../../libreria_php/clase.php';

$conexion = new clase();
$conexion->local();
$conexion->conectar();


$sql = "UPDATE servicio SET nombre = '$nombre', precio = '$precio'  WHERE  id= '$id' ";

$ejecutar = $conexion->mysqli->query($sql);

if($ejecutar){
echo json_encode(array(
'success'=>true, 
"errorMsg"=>$sql));
/*
$conexion->conectar_bdd_auditoria();
$conexion->auditoria($sql);
$conexion->auditoria_usuarios("se edito campo de la tabla servicio el id = $id ");
*/

} else {echo "ocurrio un error";}

?>