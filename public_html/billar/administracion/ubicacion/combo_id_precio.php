<?php 


include '../../../libreria_php/clase.php';
$conexion = new clase();
$conexion->local();
$conexion->conectar();

extract($_REQUEST);

$rs = $conexion->mysqli->query("SELECT  p.id as id_precio, p.nombre as tipo_precio from precio as p ");


if(!$rs)
{

printf("Error query: %s\n", $conexion->mysqli->error);

}else{


$items = array();

while($row = $rs->fetch_object()){

array_push($items, $row);
}

$result["rows"] = $items;

echo json_encode($items);


}

?>