<?php 
extract($_REQUEST);

include '../../libreria_php/clase.php';

$conexion = new clase();
$conexion->local();
$conexion->conectar();


$rs = $conexion->mysqli->query("SELECT
u.nombre,
t.id,
t.entrada,
t.salida,
TIMEDIFF(	t.salida, t.entrada) AS 'tiempo',
SUBSTRING(TIMEDIFF(	t.salida, t.entrada),1,2) AS 'hora',
ROUND(((TIME_TO_SEC(TIMEDIFF(	t.salida,	t.entrada	))/60))) AS 'minuto',
TIME_TO_SEC(TIMEDIFF(	t.salida, t.entrada )) AS 'segundo',
SUBSTRING(TIMEDIFF(	t.salida,	t.entrada ),4,2) AS 'minuto_adicional',
ROUND(ROUND(((TIME_TO_SEC(TIMEDIFF(	t.salida,	t.entrada	))/60))) * p.minuto) AS 'cancelar_minuto',
IF (
	(
		SUBSTRING(
			TIMEDIFF(t.salida, t.entrada),
			4,
			2
		)
	) > 10,
	TRUNCATE (
		TRUNCATE (
			SUBSTRING(
				TIMEDIFF(t.salida, t.entrada),
				1,
				2
			) + 1 , 0
		) * p.hora , 0
	),
	'0'
) AS 'cancelar_adicional',
t.estado as 'estado'
FROM
	tiempo AS t
JOIN ubicacion AS u ON  u.id =t.id_ubicacion
JOIN precio as p ON  p.id  =  u.tipo
WHERE
t.estado = 0
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