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
t.entrada,
t.salida,
ROUND(ROUND(((TIME_TO_SEC(TIMEDIFF(	t.salida,	t.entrada	))/60))) * p.minuto) AS 'precio_tiempo'
FROM
tiempo AS t
JOIN ubicacion AS u ON  u.id =t.id_ubicacion
JOIN precio as p ON  p.id  =  u.tipo
WHERE
t.estado = 1
AND (
t.salida >= '$buscar_desde'
AND t.salida <= '$buscar_hasta'
)
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