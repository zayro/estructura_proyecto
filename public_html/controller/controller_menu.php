<?php

include('../model/model_menu.php');

$objeto = new menu();

switch ($_SERVER['REQUEST_METHOD']) {

  case 'GET':
    
    extract($_REQUEST);
    
    if(isset($mostrar_menu)){
    $objeto->mostrar_menu();
    }
    

    break;
}