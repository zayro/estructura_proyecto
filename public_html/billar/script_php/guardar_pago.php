<?php

extract($_REQUEST);

include '../../../librerias/clase_procesos_bd.php';

$objeto = new procesos_bd();
$objeto->conectar_billar($_SESSION['empresa']);

$sql1 = "UPDATE consumo set estado = 1 where id_tiempo = '$id';";
$sql2 = "UPDATE tiempo set estado = 1, salida = now() where id = '$id';";
$sql3 = "INSERT INTO `pago` (`id_tiempo`, `producto`, `precio_consumo`, `cantidad`, `precio_tiempo`) 
SELECT
t.id ,
s.nombre,
s.precio,
c.cantidad,
ROUND(ROUND(((TIME_TO_SEC(TIMEDIFF(	now(),	t.entrada	))/60))) * p.minuto) AS 'precio_tiempo'
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
";

$datos = array();

try {
  $objeto->inicia_transaccion();
  
  $objeto->transaccion($sql1, "ACTUALIZA EL ESTADO DEL CONSUMO A CANCELADO");
  $objeto->transaccion($sql2, "ACTUALIZA EL ESTADO DEL TIEMPO A CANCELADO");
  $objeto->transaccion($sql3, "GUARDA EL HISTORIAL DEL PAGO");
  $objeto->finaliza_transaccion();
  
   $datos['suceso'] = "CONSULTA EXITOSA";
    $datos['success'] = true;
    
    
    
} catch (Exception $e) {

  $datos['suceso'] = $this->mysqli->error;
  $datos['success'] = false;
  $datos['sql'] = $sql;
  $datos['error'] = $e->getMessage();
  $objeto->cancela_transaccion();
}


echo json_encode($datos);
?>