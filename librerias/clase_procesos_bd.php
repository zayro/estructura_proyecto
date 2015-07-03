<?php

include('clase_consulta_bd.php');
include ('clase_interfas.php');

/**
 * CLASE DE PROCESOS A LA BASE DE DATOS
 *
 * En esta parte nos encargamos de crear los tipos de conexion del proyecto 
 * para poder asi administrar los tipos de permisos de acceso
 * 
 * @author MARLON ZAYRO ARIAS VARGAS
 * @version 1.0
 * @package clase
 * @category procesos
 */
class procesos_bd extends consulta_bd implements auditar {

  function procesos_bd() {
    conexion::conexiones();
  }

  function preparar_consulta($sql) {
    return $this->mysqli->prepare($sql);
  }

  function inicia_transaccion() {

    $this->mysqli->autocommit(false);
  }

  function finaliza_transaccion() {

    $this->mysqli->commit();
  }

  function cancela_transaccion() {

    $this->mysqli->rollback();
  }

  function cerrar_conexion() {

    $this->mysqli->close();
  }

  function ultimo_insert() {

    $this->mysqli->insert_id;
  }

  /**
   * MENSAJE DE AUDITORIA PARA EL USUARIO Y PARA EL SISTEMA
   * @param string $mensaje
   * @param string $sql
   */
  function auditoria($sql, $mensaje) {

    #MENSAJE DE AUDITORIA PARA EL USUARIO

    $buscar = stristr($sql, 'select');
    $buscar_mayus = stristr($sql, 'SELECT');

    if ($buscar === FALSE or $buscar_mayus === FALSE) {

      $convertir = array("'" => "|", '"' => "|");
      $accion = strtr($sql, $convertir);


      if (!isset($_SESSION['identificacion'])) {

        $sql = "  INSERT INTO `auditoria` (`ip`, `tiempo`, `usuario`, `proceso`, `mensaje`, `archivo`)	
          VALUES ('" . conexion::obtener_ip() . "', NOW(), USER(), '$accion' , '$mensaje', '" . conexion::ruta_actual() . "');";

        $auditoria_sistema = $this->mysqli->query($sql);

        if (!$auditoria_sistema) {

          return 'Error:' . $this->mysqli->error;
        }

        return "exitosa";
      } else {

        $sql = "INSERT INTO `auditoria` (`ip`, `tiempo`, `usuario`, `proceso`, `mensaje`, `archivo`)	
          VALUES ('" . conexion::obtener_ip() . "', NOW(), '" . $_SESSION['identificacion'] . "', '$accion', '$mensaje', '" . conexion::ruta_actual() . "');";

        $auditoria_sistema = $this->mysqli->query($sql);

        if (!$auditoria_sistema) {

          return 'Error:' . $this->mysqli->error;
        }

        return "exitosa";
      }
    }
    return "ESTA ENVIANDO UN SELECT";
  }

  /**
   * CUALQUIER CAMBIO DIRECTO A LA BASE DE DATOS TIENE QUE PASAR POR AQUI
   *
   * @return $datos retorna los mensajes despues de ejecutar la consulta y la auditoria
   * @throws dispara la consulta que se encuentre mal generada
   * @param string $sql se le envia la consulta a la base de datos
   * @param string $mensaje se le envia un mensaje de auditoria 
   *
   */
  function alterar_bd($sql, $mensaje) {

    /**
     * MOSTRAR EL MENSAJE EN JSON
     * @var array|null
     */
    $datos = array();

    /**
     * ARMAR SQL PARA AUDITORIA
     * @var array|null
     */
    $query_sql = array();

    /**
     * ARMAR MENSAJE PARA AUDITORIA
     * @var array|null
     */
    $mensaje_auditoria = array();

    try {

      if (!$this->mysqli->query($sql)) {

        throw new Exception("ERROR: $sql");
      }

      $afectaciones = $this->mysqli->affected_rows;


      if ($afectaciones == '0') {
        throw new Exception("NO SE ENCUENTRAS COINCIDENCIAS: $sql");
      }



      $datos['suceso'] = "CONSULTA EXITOSA";
      $datos['success'] = true;
      $datos['sql'] = $sql;
      $datos['afectaciones'] = $afectaciones;
      $datos['auditoria'] = $this->auditoria($sql, $mensaje);
    } catch (Exception $e) {

      $datos['suceso'] = $this->mysqli->error;
      $datos['success'] = false;
      $datos['sql'] = $sql;
      $datos['error'] = $e->getMessage();
    }

    return $datos;
  }

  /**
   * CUALQUIER CAMBIO DIRECTO A LA BASE DE DATOS TIENE QUE PASAR POR AQUI Y TIENE QUE ESTAR REGISTRADO
   *
   * @return $datos retorna los mensajes despues de ejecutar la consulta y la auditoria
   * @throws dispara la consulta que se encuentre mal generada
   * @param string $sql se le envia la consulta a la base de datos
   * @param string $mensaje se le envia un mensaje de auditoria 
   *
   */
  function alterar_bd_seguro($sql, $mensaje) {

    conexion::validar_session();

    /**
     * MOSTRAR EL MENSAJE EN JSON
     * @var array|null
     */
    $datos = array();

    /**
     * ARMAR SQL PARA AUDITORIA
     * @var array|null
     */
    $query_sql = array();

    /**
     * ARMAR MENSAJE PARA AUDITORIA
     * @var array|null
     */
    $mensaje_auditoria = array();

    try {

      if (!$this->mysqli->query($sql)) {

        throw new Exception("ERROR: $sql");
      }

      $afectaciones = $this->mysqli->affected_rows;


      if ($afectaciones == '0') {
        throw new Exception("NO SE ENCUENTRAS COINCIDENCIAS: $sql");
      }
#array_push($query_sql, $sql);
#array_push($mensaje_auditoria, $mensaje); 




      $datos['suceso'] = "CONSULTA EXITOSA";
      $datos['success'] = true;
      $datos['sql'] = $sql;
      $datos['afectaciones'] = $afectaciones;
      $datos['auditoria'] = $this->auditoria($sql, $mensaje);
    } catch (Exception $e) {

      $datos['suceso'] = $this->mysqli->error;
      $datos['success'] = false;
      $datos['sql'] = $sql;
      $datos['error'] = $e->getMessage();
    }

    return $datos;
  }

  /**
   * CUALQUIER CAMBIO POR TRANSACCION A LA BASE DE DATOS 
   * 
   * con este metodo podemos realizar transacciones
   * 
   * @param string $sql parametro de sql
   * @param string $mensaje parametro mensaje de auditoria
   * @return array $datos retorna arreglos de datos
   * @throws Exception disparar un error si la consulta no fue exitosa
   */
  function transaccion($sql, $mensaje) {

    conexion::validar_session();

    $datos = array();
    $consulta = $this->mysqli->query($sql);

    if (!$consulta) {
      $error = $this->mysqli->error;
      throw new Exception("ERROR: $sql :: $error :: ");
    }

    $this->auditoria($sql, $mensaje);


    $datos['suceso'] = "CONSULTA EXITOSA";
    $datos['success'] = true;
    $datos['sql'] = $sql;
    $datos['auditoria'] = $mensaje;

    return $datos;
  }

  /**
   * 
   * @param type $query
   * @throws Exception
   */
  function multiples_consultas($query) {

    conexion::validar_session();

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
          printf("------- SE EJECUTA LA SIGUIENTE CONSULTA ----------\n");
        }
      } while ($this->mysqli->next_result());
    } else {
      throw new Exception("ERROR: $query");
    }

    if ($this->mysqli->errno) {
      throw new Exception("ERROR ESPECIFICO: $this->mysqli->error");
    }

    $this->auditoria($query, "SE EJECUTARON MULTIPLES CONSULTAS A LA BASE DE DATOS");
  }

  /**
   * MANEJAR LLAMADOS A PROCEDIMIENTOS ALMACENADOS
   * 
   * devuelve la respuesta de mysql en diferentes consultas
   * 
   * @param string $sql
   * @return type
   * @throws Exception cuando sucede un error en alguna consulta
   */
  function procedimiento_almacenado($sql, $mensaje) {

    $datos = array();
    $respuesta = array();

    $error_sql = explode(";", $sql);
    try {
      $numero_consulta = 0;
      if ($this->mysqli->multi_query($sql)) {
        do {
          $numero_consulta++;
          /* almacenar primer juego de resultados */
          if ($result = $this->mysqli->store_result()) {
            while ($row = $result->fetch_object()) {
              array_push($respuesta, $row);
            }
            $result->free();
          }

          /**
           * MUESTRA DIVISION DE LOS MENSAJES DE RESPUESTA DE LAS DIFERENTES CONSULTAS
            if ($this->mysqli->more_results()) {
            echo "<br>";
            }
           * */
        } while (@$this->mysqli->next_result());
      }

      if ($this->mysqli->errno) {
        throw new Exception("ERROR CONSULTA :$numero_consulta ");
      }

      $datos['suceso'] = "CONSULTA EXITOSA";
      $datos['success'] = true;
      $datos['sql'] = $sql;
      $datos['respuestas'] = $respuesta;
      $datos['auditoria'] = $this->auditoria($sql, $mensaje);
    } catch (Exception $e) {

      $datos['suceso'] = $this->mysqli->error;
      $datos['success'] = false;
      $datos['sql'] = $error_sql[$numero_consulta];
      $datos['error'] = $e->getMessage();
    }

    return $datos;
  }

#CIERRA LA CLASE
}

?>