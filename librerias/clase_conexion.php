<?php

# 1 HORA DE TIEMPO DE SESSION
ini_set("session.cookie_lifetime", "1800");
ini_set("session.gc_maxlifetime", "1800");


ini_set('session.gc_probability', 1);
ini_set('session.gc_divisor', 100);

# zona horaria
date_default_timezone_set('America/Bogota');

# activar o desactivar mensajes de error
/*
  ini_set ('display_errors', 1);
  set_time_limit (0);
 */

session_start();

include ('clase_abstracta.php');

/**
 * CLASE DE CONEXION PRINCIPAL
 *
 * En esta parte nos encargamos de crear los tipos de conexion del proyecto 
 * para poder asi administrar los tipos de permisos de acceso
 *
 * @author MARLON ZAYRO ARIAS VARGAS <zayro8905@gmail.com>
 * @version 1.0
 * @since 2015-05-02
 * @copyright 2015 
 * @package clase
 * @category conexion
 */
class conexion extends datos {

  const VERSION = '1.0';
  const FECHA_DE_APROBACION = '2015-05-02';

  /**
   * CONTIENE LAS PROPIEDADES DE MYSQL
   * @var type objecto mysqli protegido
   */
  public $mysqli;

  /**
   * CONTIENE EL RESULTADO DE LA CONEXION: retorna los mensajes de conexion
   * @var type $array
   */
  public $resultado_conexion = array();

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

  /**
   * DATOS DE LAS CONEXIONES 
   * 
   * @return boolean
   */
  protected function conexiones() {

    $validar_conexion = false;

    $this->local();
    if ($this->conectar() == 'conectado') {
      array_push($this->resultado_conexion, $this->conectar() . ' Local');
      $validar_conexion = true;
      return true;
    } else {
      array_push($this->resultado_conexion, $this->conectar() . ' Local');
    }


    $this->externo();
    if ($this->conectar() == 'conectado') {
      array_push($this->resultado_conexion, $this->conectar() . ' externo');
      $validar_conexion = true;
      return true;
    } else {
      array_push($this->resultado_conexion, $this->conectar() . ' externo');
    }




    if (!$validar_conexion) {
      exit('<br> <strong> servidores desconectados </strong> <br>');
    }

    return false;
  }

  /**
   * SOLO SE CONECTA A UNA BASE DE DATOS
   *  
   * @return boolean
   */
   function conectar_billar() {
     $validar_conexion = false;
    $this->billar();
    if ($this->conectar() == 'conectado') {
      array_push($this->resultado_conexion, $this->conectar() . ' billar');  
       $validar_conexion = true;
      return true;
    } else {
      array_push($this->resultado_conexion, $this->conectar() . ' billar');
    }
     if (!$validar_conexion) {
      exit('<br> <strong> servidores desconectados </strong> <br>');
    }

    return false;
  }

  /**
   * SOLO IMPRIME JSON
   * 
   * @param type $array
   */
  function imprime_json($array) {
    echo json_encode($array, JSON_PRETTY_PRINT);
  }

  /**
   * VERIFICA E IMPRIMER ERRORES DE IMPRESION DE JSON
   * 
   * @return type PHP_EOL
   */
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

  /**
   * Reemplaza todos los acentos por sus equivalentes sin ellos 
   * 
   * @param type $string
   * string la cadena a sanear
   * 
   * @return type $string
   * string saneada
   */
  function limpiar_caracteres($string) {

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

  /**
   * VALIDA SI EXISTE SESSION
   * 
   */
  function validar_session() {
    if (empty($_SESSION) or empty($_SESSION['identificacion']) or ! isset($_SESSION['identificacion'])) {

      echo "sin acceso al sistema ingrese a la plataforma";
      exit();
    } else {
      return "ok";
    }
  }

  /**
   * ENVIO DE CORREOS
   * 
   * @param type $recibe recibe correos
   * @param type $envia envio correos
   * @param type $mensaje_html contenido html del correo
   * @param type $correos correos al enviar
   * @return string mensaje exitoso o no
   */
  function enviar_email($recibe, $envia, $mensaje_html, $correos) {

    #cabeceras del correo
    $headers = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    $headers .= "From: $envia < $envia >" . "\r\n";

    //$headers .= 'Cc: birthdayarchive@example.com' . "\r\n";
    //$headers .= 'Bcc: birthdaycheck@example.com' . "\r\n";
    #enviando correos
    if (mail($correos, $recibe, $mensaje_html, $headers)) {
      return 'enviado emails';
    } else {
      return 'No enviado los email: ';
    }
  }

  /**
   * OBTENER IP DE UN EQUIPO
   * 
   * @return string retorna ip
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

  /**
   * OBTENER RUTA ACTUAL DE UN ARCHIVO
   * 
   * @return string retorna ruta
   */
  function ruta_actual() {
    $ruta = getcwd();
    $raiz = $_SERVER['DOCUMENT_ROOT'];
    $script_nombre = $_SERVER['SCRIPT_FILENAME'];


    return $script_nombre;
  }

}
