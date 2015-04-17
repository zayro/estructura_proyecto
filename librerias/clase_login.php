<?php

include('clase_conexion.php');

class login extends conexion {

/*
* se crea una consulta preparada para motivos de seguridad
*
*/	

function login(){
  
conexion::conexiones();  
}
       
function logueo($usuario, $clave){
	



$stmt = "";	
/* crear una sentencia preparada */
$stmt = $this->mysqli->prepare("SELECT count(*) as encontrado, usuario, grupo, identificacion FROM usuarios WHERE usuario = ? and clave= encode( ? , 'clave') and estado = '1' ");

/* ligar parámetros para marcadores */
$stmt->bind_param("ss", $usuario, $clave);

/* ejecutar la consulta */
$stmt->execute();

/* ligar variables de resultado */
$stmt->bind_result($encontrado, $usuario, $grupo, $identificacion);

/* obtener valor */
$stmt->fetch();

if($encontrado > 0){

$_SESSION['usuario'] =  $usuario;
$_SESSION['grupo'] =  $grupo;
$_SESSION['identificacion'] =  $identificacion;

return  'exitoso';
	
}else{

session_destroy();
return  'error';	
	
}


}



}


?>