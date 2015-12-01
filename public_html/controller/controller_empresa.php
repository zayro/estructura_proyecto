<?php

include('../model/model_empresas.php');

$objeto = new empresa();

switch ($_SERVER['REQUEST_METHOD']) {

  case 'GET':
    
    extract($_REQUEST);
    
    if(isset($mostrar_empresas)){

    $objeto->mostrar_empresas();
    }
    

    break;
}