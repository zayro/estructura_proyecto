<?php

session_start();
/*
  ini_set("session.cookie_lifetime","7200");
  ini_set("session.gc_maxlifetime","7200");
  date_default_timezone_set( 'America/Bogota' );
  ini_set ('display_errors', 1);
  set_time_limit (0);
 */

/**
 * CLASE DE CONEXION PRINCIPAL
 *
 * En esta parte nos encargamos de crear los tipos de conexion del proyecto 
 * para poder asi administrar los tipos de permisos de acceso
 *
 * @author MARLON ZAYRO ARIAS VARGAS
 * @param string $mysqli Se empleara la conexion mysqli que es orientada a objetos
 * @return returna si la conexion fue exitosa
 * @version 1.0
 * @package clase\conexion
 * @copyright 2015 
 */
include ('clase_abstracta.php');

class conexion extends datos {

  const VERSION = '1.0';
  const FECHA_DE_APROBACION = '2015-05-02';

  protected $mysqli;


  /**
   * METODO CONECTAR
   *
   * esperamos los  parametro de conexion
   *
   */
  protected function conectar() {

    $this->mysqli = @new mysqli($this->servidores, $this->usuarios, $this->claves, $this->bdd);


    if ($this->mysqli->connect_error) {

      return utf8_encode($this->mysqli->connect_error);
    } else {
      $this->mysqli->set_charset("utf8");
      $this->mysqli->query("SET NAMES 'utf8'");

      return 'conectado';
    }
  }

    protected function conexiones() {
      
    $validar_conexion = "";
    
    $this->local();
    if ($this->conectar() == 'conectado') {
      array_push($this->resultado_conexion, $this->conectar() . ' Local');
      $validar_conexion = true;
    } else {
      array_push($this->resultado_conexion, $this->conectar() . ' Local');
    }


    $this->local_casa();
    if ($this->conectar() == 'conectado') {
      array_push($this->resultado_conexion, $this->conectar() . ' CASA');
      $validar_conexion = true;
      } else {
      array_push($this->resultado_conexion, $this->conectar() . ' CASA');
    }


    $this->local_rayco();
    if ($this->conectar() == 'conectado') {
      array_push($this->resultado_conexion, $this->conectar() . ' RAYCO');
      $validar_conexion = true;
      } else {
      array_push($this->resultado_conexion, $this->conectar() . ' RAYCO');
    }

    if (!$validar_conexion) {
      exit('<br> <strong> servidores desconectados </strong> <br>');
    }
  }

  function imprime_json($array) {
    echo json_encode($array, JSON_PRETTY_PRINT);
  }

  function verificar_json() {


    switch (json_last_error()) {
      case JSON_ERROR_NONE:
        echo ' - Sin errores';
        break;
      case JSON_ERROR_DEPTH:
        echo ' - Excedido tamaño máximo de la pila';
        break;
      case JSON_ERROR_STATE_MISMATCH:
        echo ' - Desbordamiento de buffer o los modos no coinciden';
        break;
      case JSON_ERROR_CTRL_CHAR:
        echo ' - Encontrado carácter de control no esperado';
        break;
      case JSON_ERROR_SYNTAX:
        echo ' - Error de sintaxis, JSON mal formado';
        break;
      case JSON_ERROR_UTF8:
        echo ' - Caracteres UTF-8 malformados, posiblemente están mal codificados';
        break;
      default:
        echo ' - Error desconocido';
        break;
    }

    return PHP_EOL;
  }

  function limpiar_caracteres($string) {
    /**
     * Reemplaza todos los acentos por sus equivalentes sin ellos
     *
     * @param $string
     *  string la cadena a sanear
     *
     * @return $string
     *  string saneada
     */
    $string = trim($string);

    $string = str_replace(
            array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'), array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'), $string
    );

    $string = str_replace(
            array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'), array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'), $string
    );

    $string = str_replace(
            array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'), array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'), $string
    );

    $string = str_replace(
            array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'), array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'), $string
    );

    $string = str_replace(
            array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'), array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'), $string
    );

    $string = str_replace(
            array('ñ', 'Ñ', 'ç', 'Ç'), array('n', 'N', 'c', 'C',), $string
    );

//Esta parte se encarga de eliminar cualquier caracter extraño
    $string = str_replace(
            array("\\", "¨", "º", "-", "~",
        "#", "@", "|", "!", "\"",
        "·", "$", "%", "&", "/",
        "(", ")", "?", "'", "¡",
        "¿", "[", "^", "`", "]",
        "+", "}", "{", "¨", "´",
        ">", "< ", ";", ",", ":",
        ".", " DE", " de", "<", ">", "  "), ' ', $string
    );


    return $string;
  }

  function validar_session() {
    if (empty($_SESSION) or empty($_SESSION['identificacion']) or ! isset($_SESSION['identificacion'])) {

      echo "sin acceso al sistema ingrese a la plataforma";
      exit();
    }
  }

  function enviar_email_gestion_documental($recibe, $envia, $mensaje_html, $correos) {
    /**
     *     To send HTML mail, the Content-type header must be set
     */
    $headers = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

    /**
     *  Additional headers
     */
    $headers .= "From: $envia < $envia >" . "\r\n";
    
//$headers .= 'Cc: birthdayarchive@example.com' . "\r\n";
//$headers .= 'Bcc: birthdaycheck@example.com' . "\r\n";
// Mail it



    if (mail($correos, $recibe, $mensaje_html, $headers)) {
      return 'enviado emails';
    } else {
      return 'No enviado los email: ';
    }
  }

  /*
   * Obtener la direccion ip del cliente
   * 
   */

  function obtener_ip() {
    if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
      $ip = getenv("HTTP_CLIENT_IP");
    else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
      $ip = getenv("HTTP_X_FORWARDED_FOR");
    else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
      $ip = getenv("REMOTE_ADDR");
    else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
      $ip = $_SERVER['REMOTE_ADDR'];
    else
      $ip = "IP desconocida";
    return($ip);
  }

}
