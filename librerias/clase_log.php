<?php

/**
 * CLASE CREAR LOS INFORMATIVOS
 *
 * Escribe lo que le pasen a un archivo de logs
 * para poder asi administrar los tipos de permisos de acceso
 * 
 * @author MARLON ZAYRO ARIAS VARGAS
 * @version 1.0
 * @package clase
 * @category log
 */
class log {

  /**
   * Escribe lo que le pasen a un archivo de logs
   * @param string $cadena texto a escribir en el log
   * @param string $tipo texto que indica el tipo de mensaje. Los valores normales son Info, Error,  
   */
  function escribir_log($cadena, $tipo) {

    # $arch = fopen(realpath('.') . "/logs/log_" . date("Y-m-d H:i:s.u") . ".txt", "a+");

    $nombre_archivo = "logs/log_" . date("Y-m-d") . ".txt";
    $arch = fopen($nombre_archivo, "a+");


    fwrite($arch, " ######################################################################### " . PHP_EOL);
    fwrite($arch, "[" . "\n HTTP_HOST: ] " . $_SERVER['HTTP_HOST']  . "\n \n\r " . PHP_EOL);
    fwrite($arch, "[" . "\n HTTP_USER_AGENT: ] " . $_SERVER['HTTP_USER_AGENT'] . "]" . "\n \n\r" . PHP_EOL);
    fwrite($arch, "[" . "\n REQUEST_URI: ]" . $_SERVER['REQUEST_URI'] .  "\n \n\r" . PHP_EOL);
    fwrite($arch, " ######################################################################### " . PHP_EOL);
    fwrite($arch, "[" . date("Y-m-d H:i:s") . " - $tipo ] " . $cadena . "\n" . PHP_EOL);
    fwrite($arch, " ######################################################################### " . PHP_EOL);
    fwrite($arch, "  " . PHP_EOL);
    fclose($arch);
  }

}
