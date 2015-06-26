<?php  

/*
$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'nombre';
$order = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
$offset = ($page-1)*$rows;
 */


$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'nombre';
$order = isset($_POST['order']) ? strval($_POST['order']) : 'asc'; 
$result = array();
 
include '../../../libreria_php/clase.php';
$conexion = new clase();
$conexion->local();
$conexion->conectar();

$rs = $conexion->mysqli->query("select count(*) from servicio");


/*
$resultado_consulta = $conexion->mysqli->query("
SELECT 
id, nombre, precio
FROM
servicio
ORDER BY $sort $order 
");
*/

$resultado_consulta = $conexion->mysqli->query("
SELECT 
id, nombre, precio
FROM
servicio
ORDER BY $sort $order 
");



if(!$resultado_consulta or !$rs)
{

printf("Error query: %s\n", $conexion->mysqli->error);

}else{

$total_campos =  $rs->fetch_row();
$result["total"] = $total_campos[0];

$items = array();

while($row = $resultado_consulta->fetch_object()){
	array_push($items, $row);
}
$result["rows"] = $items;

echo json_encode($result);


}

?>