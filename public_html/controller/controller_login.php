<?php

include('../model/model_usuario.php');

$objeto_usuario = new usuario();
$objeto_conexion = new conexion();
$objeto_consulta = new consulta_bd();

switch ($_SERVER['REQUEST_METHOD']) {

  case 'GET':
    
    extract($_REQUEST);

    if (isset($salir))
      {
      session_destroy();
      $objeto_usuario->eliminar_conectado($identificacion);
      #header('Location: ../public_html/index.html');
      #exit();
    }

    break;
    
  case 'POST':
        extract($_REQUEST);

    if ($usuario)
      {
      $ip = $objeto_conexion->obtener_ip();


      $query = ("call conectar_usuarios('$usuario', '$clave', '$empresa', '$ip');");

      $items = array();

      #array_push($items, $query);

      /* ejecutar multi consulta */
      if ($objeto_consulta->multi_query($query)) {

        do {

          /* almacenar primer juego de resultados */
          if ($result = $objeto_consulta->store_result()) {

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
          if ($objeto_consulta->more_results()) {
            #  array_push($items, "---------------"); 
          }
        } while ($objeto_consulta->next_result());
      } 
      else
        {
       array_push($items, "ocurrio un error al ejecutar"); 
      }



      print json_encode($items);
    }

    break;
}





