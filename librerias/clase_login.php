<?php

include('clase_procesos_bd.php');

class login extends procesos_bd {

/**
 * 
 * @param type $usuario
 * @param type $clave
 * @return string
 */
function logueo($usuario, $clave){
	
$stmt = "";	
/* crear una sentencia preparada */
$stmt = procesos_bd::preparar_consulta("
  SELECT count(*) as encontrado, usuario, grupo, g.nombre as nombre_grupo, identificacion 
  FROM usuarios as u join grupo as g on u.grupo  = g.id  
  WHERE usuario = ? and clave = encode( ? , 'clave') and estado = '1' ;
  ");
#$stmt = $this->mysqli->prepare("SELECT count(*) as encontrado, usuario, grupo, identificacion FROM usuarios WHERE usuario = ? and clave= ? and estado = '1' ");

/* ligar parámetros para marcadores */
$stmt->bind_param("ss", $usuario, $clave);

/* ejecutar la consulta */
$stmt->execute();

/* ligar variables de resultado */
$stmt->bind_result($encontrado, $usuario, $grupo, $nombre_grupo, $identificacion);

/* obtener valor */
$stmt->fetch();

if($encontrado > 0){

$_SESSION['usuario'] =  $usuario;
$_SESSION['grupo'] =  $grupo;
$_SESSION['nombre_grupo'] =  $nombre_grupo;
$_SESSION['identificacion'] =  $identificacion;

return  'exitoso';
	
}else{

session_destroy();
return  'Revisar datos ingresados.'.$encontrado;	
	
}

}

/**
 * RECUPERAR CLAVE
 * 
 * @param type $correo
 */
function recuperar_clave($correo){

$stmt = "";	

  /* crear una sentencia preparada */
$stmt = $this->mysqli->prepare("
  SELECT identificacion 
  FROM usuarios  
  WHERE correo = ?  and estado = '1' 
  ");

/* ligar parámetros para marcadores */
$stmt->bind_param("s", $correo);

/* ejecutar la consulta */
$stmt->execute();

/* ligar variables de resultado */
$stmt->bind_result($encontrado, $usuario, $grupo, $nombre_grupo, $identificacion);

/* obtener valor */
$stmt->fetch();


}

/**
 * AUTENTICAR CORREO
 * 
 * @param type $correo
 */
function autenticar_correo($correo){}

/**
 * CAMBIO DE CLAVE
 * 
 * @param string $usuario
 * @param string $clave
 * @param string $nueva
 */
function cambio_clave($usuario, $clave, $nueva){
  
  $sql = "
    UPDATE
    usuarios
    SET
    clave = encode('$nueva', 'clave')
    WHERE
    usuario = '$usuario'
    and
    clave = encode('$clave', 'clave')
    and
    estado  = '1'
     " ;
  
  $mensaje = "cambio de clave de usuario";
  
  return procesos_bd::alterar_bd($sql, $mensaje);
  
}

/**
 * CREACION DE USUARIOS
 * 
 * @param type $usuario
 */
function usuario_nuevo($usuario){}

#CIERRA CLASE
}
?>