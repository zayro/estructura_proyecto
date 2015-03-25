<?php

include('clase_conexion.php');
/**
 * CLASE DE PROCESOS A LA BASE DE DATOS
 *
 * En esta parte nos encargamos de crear los tipos de conexion del proyecto 
 * para poder asi administrar los tipos de permisos de acceso
 * 
 * @method procesos_bd () se realiza la conexion
 * @method usuarios() se realiza la auditoria usuario
 * @method privada () se realiza la auditoria privada
 * @author MARLON ZAYRO ARIAS VARGAS
 * @version 1.0
 * @package clase\consulta
 */
class consulta_bd extends conexion{
  
   public $guardar_registros = array();
   
  function consulta_bd() {
    conexion::local();
    return conexion::conectar();
  }
  
  
    /**
   * LAS CONSULTAS SERAN DEVUELTAS EN FORMATOS JSON
   *
   * @return $datos retorna los mensajes despues de ejecutar la consulta y la auditoria
   * @throws dispara la consulta que se encuentre mal generada
   * @param string $sql se le envia la consulta a la base de datos
   *
   */
  function json_bd($sql) {

    /**
     * MOSTRAR EL MENSAJE EN JSON
     * @var array|null
     */
    $datos = array();

    /**
     * GUARDA TEMPORALMENTE LOS RESULTADOS
     * @var array|null
     */
    $items = array();

    $resultado = $this->mysqli->query($sql);


    if (!$resultado) {

      throw new Exception("ERROR: $sql");
    }


    while ($row = $resultado->fetch_object()) {

      array_push($items, $row);
    }

    $datos["registros"] = $items;

    return $datos;
  }

    
    function consulta_unida($sql) {

    $resultado = $this->mysqli->query($sql);

    while ($registros = $resultado->fetch_object()) {

      array_push($this->guardar_registros, $registros);

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
          $imagenes[] =  $valor;
          
          $row->$key = $imagenes;
        }
      }



      array_push($items, $row);
    }

    $result["registros"] = $items;
    echo json_encode($result);
  }
  
  
  
}
