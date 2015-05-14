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
   #public abstract function operar();
  
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
    $this->bdd = 'parqueadero';
  }
  

    
}

?>