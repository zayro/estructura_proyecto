<?php

include '../../../../librerias/clase_procesos_bd.php';
$objeto = new procesos_bd();
$objeto->conectar_parqueadero();

if (extract($_REQUEST)) {

  try {

    $sql = "SELECT
precios.nombre as vehiculo ,
	vehiculo.tiempo_entrada,
	now() AS tiempo_salida,
	precios.salir as margen_salida,
precios.precio_hora,
precios.precio_minuto,
	# CAMPO
	TIMEDIFF(
		NOW(),
		vehiculo.tiempo_entrada
	) AS DIFERENCIA,
	# CAMPO
	HOUR (
		TIMEDIFF(
			NOW(),
			vehiculo.tiempo_entrada
		)
	) AS HORA,
	# CAMPO
	MINUTE (
		TIMEDIFF(
			NOW(),
			vehiculo.tiempo_entrada
		)
	) AS MINUTO,
	# CAMPO
	ROUND(
		TIMESTAMPDIFF(
			MINUTE,
			vehiculo.tiempo_entrada,
			NOW()
		) * precios.precio_minuto
	) AS cancelar_minutos,
	# CAMPO
	ROUND(
		TIMESTAMPDIFF(
			HOUR,
			vehiculo.tiempo_entrada,
			NOW()
		) * precios.precio_hora
	) AS cancelar_horas,
	# CAMPO
	(
		IF (
			#CONDICION
			MINUTE (
				TIMEDIFF(
					NOW(),
					vehiculo.tiempo_entrada
				)
			) > precios.salir,
			#CONDICION SI ES VERDADERO
			(
				TIMESTAMPDIFF(
					HOUR,
					vehiculo.tiempo_entrada,
					NOW()
				) * precios.precio_hora
			) + precios.precio_hora,
			#CONDICION SI ES FALSO
			HOUR (
				TIMEDIFF(
					NOW(),
					vehiculo.tiempo_entrada
				)
			) * precios.precio_hora
		)
	) AS PAGAR 
# TABLA
FROM
	vehiculo,
	precios
WHERE
	placa = '$placa'
AND estado = '0'
AND vehiculo.vehiculo = precios.id;";

    $consultar = $objeto->json_bd($sql);



    $cancelar = (isset($consultar[0]->PAGAR) or ! empty($consultar[0]->PAGAR)) ? $consultar[0]->PAGAR : '00000';

    $resultados = array();

    $objeto->inicia_transaccion();

    $sql = "UPDATE vehiculo SET estado = 'cancelado', tiempo_salida = NOW(),  valor = '$cancelar', factura = concat('FACT - ', id) WHERE estado = '0' AND placa = '$placa' ";
    $mensaje = "SE ACTUALIZO VEHICULO";

    $resultados = $objeto->transaccion($sql, $mensaje);
    $resultados['placa'] = $placa;

    $objeto->finaliza_transaccion();
  } catch (Exception $e) {

    
    $resultados['suceso'] = $e->getMessage();
    #$resultados['code'] = $e->getCode();
    #$resultados['linea'] = $e->getLine();
    $resultados['rastro'] = $e->getTrace();
    #$resultados['rastreo'] = $e->getTraceAsString();
    #$resultados['previos'] = $e->getPrevious();
    $resultados['Archivo'] = $e->getFile();
    $resultados['conexion'] = $objeto->resultado_conexion;
    $resultados['success'] = false;


    $objeto->cancela_transaccion();
  }

  $objeto->imprime_json($resultados);
}


