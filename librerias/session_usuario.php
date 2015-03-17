<?php
session_start();
/*
#$_SESSION['usu_nombres'] = "MARLON ZAYRO";
#$_SESSION['usu_apellidos'] = "ARIAS VARGAS";
#$_SESSION['usu_cedula'] = "1098669883";
*/
$numero = count($_SESSION);
$tags = array_keys($_SESSION);// obtiene los nombres de las varibles
$valores = array_values($_SESSION);// obtiene los valores de las varibles

$datos = array();
if($numero > 0){

for($i=0;$i<$numero;$i++){

$datos[$tags[$i]] =  utf8_encode($valores[$i]);

}

}


echo json_encode($datos);

?>