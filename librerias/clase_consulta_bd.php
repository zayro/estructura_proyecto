<?php

include('clase_conexion.php');

/**
 * CLASE DE PROCESOS A LA BASE DE DATOS
 *
 * En esta parte nos encargamos de crear los tipos de conexion del proyecto 
 * para poder asi administrar los tipos de permisos de acceso
 * 
 * @method consulta_bd () se realiza la conexion es el constructor
 * @author MARLON ZAYRO ARIAS VARGAS
 * @version 1.0
 * @package clase
 * @category consulta
 */
class consulta_bd extends conexion {

  /**
   * guarda los resultados de consulta unida
   * @var type $guardar_registros 
   */
  public $guardar_registros = array();

  function consulta_bd() {
    conexion::conexiones();
  }

  /**
   * LAS CONSULTAS SERAN DEVUELTAS EN FORMATOS JSON
   *
   * 
   * @param type $sql se le envia la consulta a la base de datos
   * @return $sql retorna los mensajes despues de ejecutar la consulta y la auditoria
   * @throws Exception dispara la consulta que se encuentre mal generada
   *
   */
  function json_bd($sql) {

   conexion::cabecera_json();
    #MOSTRAR EL MENSAJE EN JSON
     
    $datos_json = array();

    
    #GUARDA TEMPORALMENTE LOS RESULTADOS
    
    $items = array();

    $resultado_json = $this->mysqli->query($sql);


    if (!$resultado_json) {

      throw new Exception("ERROR: $sql");
    }


    while ($row = $resultado_json->fetch_object()) {

      array_push($items, $row);
    }

    # iberar el conjunto de resultados
    $resultado_json->close();



    return $items;
  }

  /**
   * 
   * @param type $sql se van guardando las consultas en $guarda_registros
   */
  function consulta_unida($sql) {

    $resultado = $this->mysqli->query($sql);

    while ($registros = $resultado->fetch_object()) {

      array_push($this->guardar_registros, $registros);
    }
  }

   /**
   * SE EJECUTA CONSULTAS SOLO SELECT
   * 
   * @param type $sql se recibe la consulta para ejecutar solo select
   * @return type
   */
  function consulta($sql) {
    
    $buscar = stristr($sql, 'select');
       
    if ($buscar) {
     return $this->mysqli->query($sql);      
    }else{
      return "NO CUMPLE LA CONDICION SELECT";
    }
   
    return false;
  }
  
  private function multi_consulta($query) {
    /* ejecutar multi consulta */
    if ($this->mysqli->multi_query($query)) {
      do {
        /* almacenar primer juego de resultados */
        if ($result = $this->mysqli->store_result()) {
          while ($row = $result->fetch_row()) {
            printf("%s\n", $row[0]);
          }
          $result->free();
        }
        /* mostrar divisor */
        if ($this->mysqli->more_results()) {
          printf("-----------------\n");
        }
      } while (@$this->mysqli->next_result());
    }
  }

  private function estructura_consultas_multiples_anidadas() {
    $sql1 = "";
    $resultado = $conexion->mysqli->query($sql1);

    while ($row = $resultado->fetch_object()) {

      $sql2 = "";
      $resultado_documentos = $conexion->mysqli->query($sql2);

      $imagenes = array();
      while ($row_documentos = $resultado_documentos->fetch_object()) {
        foreach ($row_documentos as $key => $valor) {


          # agrega dentro de un objeto un array
          $imagenes[] = $valor;

          $row->$key = $imagenes;
        }
      }



      array_push($items, $row);
    }

    $result["registros"] = $items;
    echo json_encode($result);
  }

}