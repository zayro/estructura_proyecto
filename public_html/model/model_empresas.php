<?php

require('../../librerias/clase_procesos_bd.php');

class empresa extends procesos_bd {
  
  public function mostrar_empresas() {
    
    $result = consulta_bd::json_bd("SELECT id, nombre from empresas");
    print json_encode($result);
  }

}