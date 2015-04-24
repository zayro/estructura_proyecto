<?php

include('clase_consulta_bd.php');

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
 * @package clase\procesos 
 */
include ('clase_interfas.php');

class procesos_bd extends consulta_bd implements auditoria {

  function procesos_bd() {
    conexion::conexiones();
   
  }

  public function auditoria_usuario($mensaje) {

    if (!isset($_SESSION['usuario'])) {

      $this->mysqli->query("
INSERT INTO `auditoria_usuario` (`ip`, `tiempo`, `usuario`, `proceso`)	
VALUES('" . $_SERVER['REMOTE_ADDR'] . "',  NOW(), USER(),  '$mensaje');
");
    } else {

      $this->mysqli->query("
INSERT INTO `auditoria_usuario` (`ip`, `tiempo`, `usuario`, `proceso`) 
VALUES ( '" . $_SERVER['REMOTE_ADDR'] . "',  NOW(),  '" . $_SESSION['usuario'] . "', '$mensaje');
");
    }
  }

  public function auditoria_privada($sql) {
    
    $buscar = stristr($sql, 'select');
    $buscar_mayus = stristr($sql, 'SELECT');
    
    if ($buscar === FALSE or $buscar_mayus === FALSE) {
      $convertir = array("'" => "|", '"' => "|");
      $accion = strtr($sql, $convertir);

      if (!isset($_SESSION['usuario'])) {
        $this->mysqli->query("INSERT INTO `auditoria` (`ip`, `tiempo`, `usuario`, `proceso`)	
VALUES ('" . $_SERVER['REMOTE_ADDR'] . "', NOW(), USER(), '$sql');");
      } else {
        $this->mysqli->query("INSERT INTO `auditoria` (`ip`, `tiempo`, `usuario`, `proceso`)	
VALUES ('" . $_SERVER['REMOTE_ADDR'] . "', NOW(), '" . $_SESSION['usuario'] . "', '$sql');");
      }
    }
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
#array_push($query_sql, $sql);
#array_push($mensaje_auditoria, $mensaje); 

      $this->auditoria_usuario($mensaje);
      $this->auditoria_privada($sql);

      $datos['suceso'] = "CONSULTA EXITOSA";
      $datos['success'] = true;
      $datos['sql'] = $sql;
      $datos['afectaciones'] = $afectaciones;
      $datos['auditoria'] = $mensaje;
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
   * @return $datos retorna los mensajes despues de ejecutar la consulta y la auditoria
   * @throws dispara la consulta que se encuentre mal generada
   * @param string $sql se le envia la consulta a la base de datos
   * @param string $mensaje se le envia un mensaje de auditoria 
   *
   */
  function transaccion($sql, $mensaje) {

    $datos = array();
    $consulta = $this->mysqli->query($sql);
   
    if (!$consulta) {
      $error = $this->mysqli->error;
      throw new Exception("ERROR: $sql :: $error :: ");
    }
    
    

    $this->auditoria_usuario($mensaje);
    $this->auditoria_privada($sql);

    $datos['suceso'] = "CONSULTA EXITOSA";
    $datos['success'] = true;
    $datos['sql'] = $sql;
    $datos['auditoria'] = $mensaje;

    return $datos;
  }

#CIERRA LA CLASE
}

?>