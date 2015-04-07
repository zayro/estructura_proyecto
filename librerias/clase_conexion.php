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
include ('clase_interfas.php');

class conexion {

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

  protected function local() {
    $this->servidores = 'localhost:3308';
    $this->usuarios = 'root';
    $this->claves = 'zayro2014';
    $this->bdd = 'estructura_proyecto';
  }

  protected function local_casa() {
    $this->servidores = 'localhost';
    $this->usuarios = 'root';
    $this->claves = 'zayro';
    $this->bdd = 'transito';
  }

  protected function local_rayco() {
    $this->servidores = 'localhost:3308';
    $this->usuarios = 'root';
    $this->claves = 'zayro2014';
    $this->bdd = 'estructura_proyecto';
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

}
