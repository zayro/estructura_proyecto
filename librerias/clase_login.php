<?php

include('clase_procesos_bd.php');

class login extends procesos_bd {

/**
 * 
 * @param string $usuario
 * @param string $clave
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
 * @param string $correo
 */
function recuperar_clave($usuario){

  $sql = "
    SELECT
    decode('$correo','clave') as clave
    FROM
    usuarios       
    WHERE
    usuario = '$usuario'
    and
    estado  = '1'
     " ;

}

/**
 * AUTENTICAR CORREO
 * 
 * @param string $correo
 */
function autenticar_correo($usuario, $correo){
  
  $usuario_decode = base64_decode($usuario);
  $correo_decode = base64_decode($correo);
  
    $sql = "
    UPDATE
    usuarios
    SET
    estado = '1'
    WHERE
    usuario = '$usuario_decode'
    and
    correo = '$correo_decode'
     " ;

    $mensaje = "CORREO AUTENTICADO";
  
  return procesos_bd::alterar_bd($sql, $mensaje);
  
  
}

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
  
  $mensaje = "CAMBIO CLAVE DE USUARIO";
  
  return procesos_bd::alterar_bd($sql, $mensaje);
  
}

/**
 * DESACTIVAR USUARIO
 * 
 * @param string $usuario
 * @param string $clave
  */
function desactivar_usuario($usuario, $clave){
  
  $sql = "
    UPDATE
    usuarios
    SET
    estado  = '0'
    WHERE
    usuario = '$usuario'
    and
    clave = encode('$clave', 'clave')
    and
    estado  = '1'
     " ;
  
  $mensaje = "DAR DE BAJA AL USUARIO";
  
  return procesos_bd::alterar_bd($sql, $mensaje);
  
}

/**
 * CREACION DE USUARIOS
 * 
 * @param string $usuario
 * @param string $clave
 * @param string $correo
 * @param string $grupo
 * @param string $identificacion
 */
function usuario_nuevo($usuario, $clave, $correo, $grupo, $identificacion){

    $sql = "
    INSERT INTO
    usuarios
    (usuario, clave, correo, grupo, identificacion)
    VALUES
    ($usuario, $clave, $correo, $grupo, $identificacion)
     " ;
  
  $mensaje = "INSERTO UN NUEVO USUARIO";
  
  return procesos_bd::alterar_bd($sql, $mensaje);
  
}

#CIERRA CLASE
}
?>