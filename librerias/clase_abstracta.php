<?php

/**
 * CLASE ABSTRACTA DE DATOS
 * 
 * NOS PROTEGE LOS METODOS PARA NO SER UTILIZADOS EN LA SUBCLASE
 *
 * metodos abstractos solo son para ser utilizados en clases
 * 
 * @author MARLON ZAYRO ARIAS VARGAS
 * @version 1.0
 * @package abstracion
 * @category datos
 */
abstract class datos {

  protected function local() {
    $this->servidores = 'localhost:3306';
    $this->usuarios = 'zayro';
    $this->claves = 'zayro2014';
    $this->bdd = 'estructura_proyecto';
  }

  protected function externo() {
    $this->servidores = 'localhost:3306';
    $this->usuarios = 'zayro';
    $this->claves = 'zayro2015';
    $this->bdd = 'estructura_proyecto';
  }

  protected function principal() {
    $this->servidores = 'localhost:3306';
    $this->usuarios = 'zayro';
    $this->claves = 'zayro2014';
    $this->bdd = '';
  }

  protected function billar($bdd) {
    $this->servidores = 'localhost:3306';
    $this->usuarios = 'zayro';
    $this->claves = 'zayro2014';
    $this->bdd = $bdd;
  }

  protected function parqueadero($bdd) {
    $this->servidores = 'localhost:3306';
    $this->usuarios = 'zayro';
    $this->claves = 'zayro2014';
    $this->bdd = '';
  }
  
   public function setCabecera($estado) {
    $respuesta = $this->getCodEstado($estado);
    header("HTTP/1.1 $estado $respuesta ");
    $this->cabecera_json();
  }
  
    public function getCodEstado($estado) {
    $verificar_estado = array(
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        204 => 'No Content',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        500 => 'Internal Server Error');
    $respuesta = ($verificar_estado[$estado]) ? $verificar_estado[$estado] : $estado[500];
    return $respuesta;
  }

  protected function cabecera_cors() {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Method: POST, GET, OPTIONS");
  }

  protected function cabecera_html() {
    header("Content-Type:text/html");
  }

  protected function cabecera_csv() {
    header("Content-Type: application/csv");
  }

  protected function cabecera_txt() {
    header("Content-Type:text/plain");
  }

  protected function cabecera_pdf() {
    header("Content-type:application/pdf");
  }

  protected function cabecera_json() {
    header('Content-Type: application/json');
  }

  protected function cabecera_word() {
    header('Content-type: application/vnd.ms-word');
    header('Content-Type: application/msword');
  }

  protected function cabecera_excel() {
    header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
    header("Content-type:   application/x-msexcel; charset=utf-8");
  }

  protected function cabecera_descarga($nombre, $extension) {
    header("Content-Disposition:attachment;filename='$nombre.$extencion'");
    header("Pragma: no-cache");
    header("Expires: 0");
  }

}

?>