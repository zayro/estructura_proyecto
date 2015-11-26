<?php
session_start();

$numero = count($_SESSION);
$tags = array_keys($_SESSION);// obtiene los nombres de las varibles
$valores = array_values($_SESSION);// obtiene los valores de las varibles

$datos = array();
if($numero > 0){

for($i=0;$i<$numero;$i++){

$datos[$tags[$i]] =  $valores[$i];

}

}
echo json_encode($datos);

?>