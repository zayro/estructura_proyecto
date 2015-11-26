<?php 

extract($_REQUEST);

include 'cabecera_busqueda.php';
$objeto = new consulta_bd();
$objeto->conectar_billar($_SESSION['empresa']);


$sql = "SELECT
c.id,
s.nombre,
s.precio,
c.cantidad,
u.nombre as 'ubicacion',
c.estado as 'estado producto'
FROM
	tiempo AS t
JOIN ubicacion AS u ON  u.id =t.id_ubicacion
JOIN precio as p ON  p.id  =  u.tipo
JOIN consumo as c ON c.id_tiempo = t.id
JOIN servicio as s ON c.id_servicio =  s.id
WHERE
t.estado = 0
AND
t.id = '$id'
;
";
$datos = $objeto->json_bd($sql);
echo json_encode($datos);



?>