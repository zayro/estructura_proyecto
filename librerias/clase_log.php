<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of clase_log
 *
 * @author Usuario
 */
class log {

  /**
   * Escribe lo que le pasen a un archivo de logs
   * @param string $cadena texto a escribir en el log
   * @param string $tipo texto que indica el tipo de mensaje. Los valores normales son Info, Error,  
   *                                       Warn Debug, Critical
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
