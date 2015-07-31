<?php


include "../../../../librerias/clase_consulta_bd.php";
$objeto = new consulta_bd();
$objeto->conectar_parqueadero($_SESSION['empresa']);

if (extract($_REQUEST)) {

  $sql = "SELECT
placa,
tiempo_entrada,
tiempo_salida,
factura,
valor
FROM
vehiculo	
WHERE
placa = '$placa'
AND estado = 'cancelado'
order by tiempo_salida desc
";

  try {

    $resultados = $consultar = $objeto->json_bd($sql);
  } catch (Exception $e) {


    $resultados['suceso'] = $e->getMessage();
    $resultados['code'] = $e->getCode();
    $resultados['linea'] = $e->getLine();
    $resultados['rastro'] = $e->getTrace();
    $resultados['rastreo'] = $e->getTraceAsString();
    $resultados['previos'] = $e->getPrevious();
    $resultados['Archivo'] = $e->getFile();
    $resultados['conexion'] = $objeto->resultado_conexion;
    $resultados['success'] = false;
  }

  $objeto->imprime_json($resultados);
}


