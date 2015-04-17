<?php

/*
 * NOS PROTEGE LOS METODOS PARA NO SER UTILIZADOS EN LA SUBCLASE
 *
 *
 */

abstract class datos {
   #public abstract function operar();
  
    protected function local() {
    $this->servidores = 'localhost:3308';
    $this->usuarios = 'root';
    $this->claves = 'zayro2014';
    $this->bdd = 'zayro2014';
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
  

    
}

?>