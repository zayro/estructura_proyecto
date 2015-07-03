<?php

extract($_REQUEST);

include '../../../librerias/clase_consulta_bd.php';

$objeto = new consulta_bd();
$objeto->conectar_billar();

$sql = "SELECT
u.nombre,
t.id,
t.entrada,
now() as 'salida',
TIMEDIFF(	now() , t.entrada) AS 'tiempo',
SUBSTRING(TIMEDIFF(	now(), t.entrada),1,2) AS 'hora',
((TIME_TO_SEC(TIMEDIFF(	now(),	t.entrada	))/60)) AS 'minuto',
 /* ROUND(((TIME_TO_SEC(TIMEDIFF(	now(),	t.entrada	))/60))) AS 'minuto', */
TIME_TO_SEC(TIMEDIFF(	now(), t.entrada )) AS 'segundo',
SUBSTRING(TIMEDIFF(	now(),	t.entrada ),4,2) AS 'minuto_adicional',
ROUND(ROUND(((TIME_TO_SEC(TIMEDIFF(	now(),	t.entrada	))/60))) * p.minuto) AS 'cancelar_minuto',
IF (
(
SUBSTRING(
TIMEDIFF(now(), t.entrada),
4,
2
)
) > 10,
TRUNCATE (
TRUNCATE (
SUBSTRING(
TIMEDIFF(now(), t.entrada),
1,
2
) + 1 , 0
) * p.hora , 0
),
'0'
) AS 'cancelar_adicional',
t.estado as 'estado',
(SELECT
sum(truncate(se.precio * co.cantidad,0))
FROM
tiempo AS ti
JOIN ubicacion AS ub ON  ub.id =ti.id_ubicacion
JOIN precio as pr ON  pr.id  =  ub.tipo
JOIN consumo as co ON co.id_tiempo = ti.id
JOIN servicio as se ON co.id_servicio =  se.id
WHERE
ti.estado = 0
AND
ti.id = t.id) AS 'total_consumo'
FROM
tiempo AS t
JOIN ubicacion AS u ON  u.id = t.id_ubicacion
JOIN precio as p ON  p.id  =  u.tipo
WHERE
t.estado = 0
ORDER BY
u.nombre";

$result = $objeto->json_bd($sql);

echo json_encode($result);




?>