<?php

/**
 * CLASE DE CARGAR DE ARCHIVOS
 *
 * En esta parte nos encargamos de crear un manejador de cargas
 *
 * 
 * @author MARLON ZAYRO ARIAS VARGAS
 * @version 1.0
 * @package clase
 * @category carga_archivos
 */
class carga_archivos {

  public $ruta = "";
  public $documento = "";
  public $error, $nombre, $peso, $tipo, $tmp, $guardar = "";
  public $rest_imagenes = array(
      'jpg' => 'image/jpeg',
      'png' => 'image/png',
      'gif' => 'image/gif',
  );

  public function carga_archivos($ruta, $documento) {

    $this->ruta = $ruta;
    $this->documento = $documento;

    /* propiedades de carga de archivos */
    $this->error = $this->documento['error'];
    $this->peso = $this->documento['size'];
    $this->nombre = $this->documento['name'];
    $this->tmp = $this->documento['tmp_name'];
    $this->tipo = $this->documento['type'];
  }

  function tipo_error() {
    switch ($this->error) {
      case UPLOAD_ERR_OK:
        throw new Exception('No file.');
        break;
      case UPLOAD_ERR_NO_FILE:
        throw new Exception('No file sent.');
        break;
      case UPLOAD_ERR_INI_SIZE:
        throw new Exception('No file init.');
        break;
      case UPLOAD_ERR_FORM_SIZE:
        throw new Exception('Exceeded filesize limit.');
        break;
      default:
        throw new Exception('Unknown errors.');
    }
  }

  function validar_error() {
    if (!isset($this->error)) {
      $this->tipo_error();
    }
  }

  function validar_peso($restriccion) {
    foreach ($this->peso as $peso) {
      if ($peso > $restriccion) {
        throw new Exception('excedio el peso del archivo.');
      }
    };
  }

  function validar_archivo_array($restriccion) {
    foreach ($this->tipo as $tipo) {
      if (false === array_search($tipo, $restriccion, true)) {
        throw new Exception('el tipo de archivo no coincide.');
      }
    };
  }

  function validar_archivo_string($restriccion) {

    foreach ($this->tipo as $tipo) {
      if ($tipo != $restriccion) {
        throw new Exception('el tipo de archivo no coincide.');
      }
    };
  }

  function info_archivos($arr) {
    foreach ($arr as $key => $all) {
      foreach ($all as $i => $val) {
        $new[$i][$key] = $val;
      }
    }
    return $new;
  }

  function guardar_archivo() {

    for ($i = 0; count($this->nombre) > $i; $i++) {
      if (!move_uploaded_file($this->tmp[$i], $this->ruta . $this->nombre[$i])) {
        throw new Exception('no se pudo guardar archivos');
      }
    }
  }

}
