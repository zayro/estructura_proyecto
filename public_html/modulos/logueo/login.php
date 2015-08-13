<?php

require('../../../librerias/clase_consulta_bd.php');

$objeto = new consulta_bd();

#$resultado = $objeto->multi_consulta("call conectar_usuarios('zayro', 'zayro1');");
header('Content-Type: application/json');

$ip = $objeto->obtener_ip();

if (extract($_REQUEST)) {
  $query = ("call conectar_usuarios('$usuario', '$clave', '$empresa', '$ip');");

  $items = array();


  /* ejecutar multi consulta */
  if ($objeto->multi_query($query)) {

    do {

      /* almacenar primer juego de resultados */
      if ($result = $objeto->store_result()) {

        while ($row = $result->fetch_object()) {
          array_push($items, $row);

          if ($row->success == 'true') {

            $_SESSION['usuario'] = $row->usuario;
            $_SESSION['grupo'] = $row->grupo;
            $_SESSION['nombre_grupo'] = $row->nombre_grupo;
            $_SESSION['identificacion'] = $row->identificacion;
            $_SESSION['codigo_empresa'] = $row->codigo_empresa;
            $_SESSION['empresa'] = $row->empresa;
            $_SESSION['imagen'] = $row->imagen;
            $_SESSION['ip'] = $ip;
          }
          
        
          
          
          
        }

        $result->free();
      }
      /* mostrar divisor */
      if ($objeto->more_results()) {
        #printf("-----------------\n");
      }
    } while ($objeto->next_result());
  }


  echo json_encode($items);
}
?>