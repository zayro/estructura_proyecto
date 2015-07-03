<?php 


include '../../../librerias/clase_consulta_bd.php';

$objeto = new consulta_bd();
$objeto->conectar_billar();

$sql = "SELECT
u.nombre,
t.id
FROM
tiempo AS t
JOIN ubicacion AS u ON  u.id =t.id_ubicacion
JOIN precio as p ON  p.id  =  u.tipo
WHERE
t.estado = 0
ORDER BY u.nombre
";


$result = $objeto->json_bd($sql);

echo json_encode($result);


?>