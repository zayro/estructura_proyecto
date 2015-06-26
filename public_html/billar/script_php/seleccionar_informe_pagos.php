<?php 

//extract($_REQUEST);


$buscar_desde = isset($_REQUEST['buscar_desde']) ? $_REQUEST['buscar_desde'] : "";
$buscar_hasta = isset($_REQUEST['buscar_hasta']) ? $_REQUEST['buscar_hasta'] : "";



include '../../libreria_php/clase.php';

$conexion = new clase();
$conexion->local();
$conexion->conectar();


$rs = $conexion->mysqli->query("SELECT
t.id,
t.salida,
u.nombre,
p.producto,
p.cantidad,
p.precio_consumo
FROM
pago AS p
JOIN tiempo AS t ON p.id_tiempo = t.id
JOIN ubicacion AS u ON t.id_ubicacion = u.id
WHERE
estado = 1
and (t.salida >= '$buscar_desde' and  t.salida <=  '$buscar_hasta' )
order by t.salida desc
;
");


if(!$rs)
{

printf("Error query: %s\n", $conexion->mysqli->error);

}else{


$items = array();

while($row = $rs->fetch_object()){

array_push($items, $row);
}

$result["rows"] = $items;

echo json_encode($result);


}

?>