<?php


require('clase_consulta_bd.php');

$objeto = new consulta_bd();

if(!empty($_SESSION['identificacion'])){
$conectado = $objeto->usuario_online();  
}else{
$datos["session"] = "no hay una session iniciada";  
@session_destroy();
}


$numero = count($_SESSION);
$tags = array_keys($_SESSION);// obtiene los nombres de las varibles
$valores = array_values($_SESSION);// obtiene los valores de las varibles

$datos = array();

if($numero > 0){

for($i=0;$i<$numero;$i++){

$valor_session = (!empty($valores[$i]) and isset($valores[$i]) and !is_null($valores[$i])  and  !is_array($valores[$i])) ? utf8_encode($valores[$i]) : $valores[$i];

$datos[$tags[$i]] =  $valor_session;

}


$datos["conectado"] = $conectado;  
}else{

$datos["session"] = "no hay una session iniciada";  
@session_destroy();
    
}


echo json_encode($datos);

?>