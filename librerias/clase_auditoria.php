<?php


include 'clase_conexion.php';

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of auditoria
 *
 * @author Usuario
 */


class auditoria_proyecto extends conexion{
    
    
    
    
function auditoria_credito_gestion_documental($sql)
{
$buscar = stristr($sql, 'select');
$buscar_mayus = stristr($sql, 'SELECT');
if($buscar === FALSE or $buscar_mayus === FALSE) {
$convertir = array("'" => "/", '"' => "/");
$accion =  strtr($sql, $convertir);

if (!isset($_SESSION['usu_cedula']))
{
$this->mysqli->query("INSERT INTO `gd_auditoria` (`ip`, `tiempo`, `usuario`, `proceso`)	 VALUES ('".$_SERVER['REMOTE_ADDR']."', NOW(), USER(), '$accion');") ;
}else{
$this->mysqli->query("INSERT INTO `gd_auditoria` (`ip`, `tiempo`, `usuario`, `proceso`)	 VALUES ('".$_SERVER['REMOTE_ADDR']."', NOW(), '".$_SESSION['usu_cedula']."', '$accion');");	
}
} 
}

function auditoria_usuarios_credito_gestion_documental($accion)
{
if (!isset($_SESSION['usu_cedula']))
{
$this->mysqli->query("INSERT INTO `gd_auditoria_usuario` (`ip`, `tiempo`, `usuario`, `proceso`) VALUES ('".$_SERVER['REMOTE_ADDR']."', NOW(), USER(), '$accion');") ;
}else{
$this->mysqli->query("INSERT INTO `gd_auditoria_usuario` (`ip`, `tiempo`, `usuario`, `proceso`) VALUES ('".$_SERVER['REMOTE_ADDR']."', NOW(), '".$_SESSION['usu_cedula']."', '$accion');");	
}
}


function auditoria_nomina($sql)
{
$buscar = stristr($sql, 'select');
$buscar_mayus = stristr($sql, 'SELECT');
if($buscar === FALSE or $buscar_mayus === FALSE) {
$convertir = array("'" => "/", '"' => "/");
$accion =  strtr($sql, $convertir);

if (!isset($_SESSION['usu_cedula']))
{
$this->mysqli->query("INSERT INTO `gdn_auditoria` (`ip`, `tiempo`, `usuario`, `proceso`)	 VALUES ('".$_SERVER['REMOTE_ADDR']."', NOW(), USER(), '$accion');") ;
}else{
$this->mysqli->query("INSERT INTO `gdn_auditoria` (`ip`, `tiempo`, `usuario`, `proceso`)	 VALUES ('".$_SERVER['REMOTE_ADDR']."', NOW(), '".$_SESSION['usu_cedula']."', '$accion');");	
}
} 
}


function auditoria_usuarios_nomina($accion)
{
if (!isset($_SESSION['usu_cedula']))
{
$this->mysqli->query("INSERT INTO `gdn_auditoria_usuario` (`ip`, `tiempo`, `usuario`, `proceso`) VALUES ('".$_SERVER['REMOTE_ADDR']."', NOW(), USER(), '$accion');") ;
}else{
$this->mysqli->query("INSERT INTO `gdn_auditoria_usuario` (`ip`, `tiempo`, `usuario`, `proceso`) VALUES ('".$_SERVER['REMOTE_ADDR']."', NOW(), '".$_SESSION['usu_cedula']."', '$accion');");	
}
}


function auditoria_areas($sql)
{
$buscar = stristr($sql, 'select');
$buscar_mayus = stristr($sql, 'SELECT');
if($buscar === FALSE or $buscar_mayus === FALSE) {
$convertir = array("'" => "/", '"' => "/");
$accion =  strtr($sql, $convertir);

if (!isset($_SESSION['usu_cedula']))
{
$this->mysqli->query("INSERT INTO `gda_auditoria` (`ip`, `tiempo`, `usuario`, `proceso`)	 VALUES ('".$_SERVER['REMOTE_ADDR']."', NOW(), USER(), '$accion');") ;
}else{
$this->mysqli->query("INSERT INTO `gda_auditoria` (`ip`, `tiempo`, `usuario`, `proceso`)	 VALUES ('".$_SERVER['REMOTE_ADDR']."', NOW(), '".$_SESSION['usu_cedula']."', '$accion');");	
}
} 
}


function auditoria_usuarios_areas($accion)
{
if (!isset($_SESSION['usu_cedula']))
{
$this->mysqli->query("INSERT INTO `gda_auditoria_usuario` (`ip`, `tiempo`, `usuario`, `proceso`) VALUES ('".$_SERVER['REMOTE_ADDR']."', NOW(), USER(), '$accion');") ;
}else{
$this->mysqli->query("INSERT INTO `gda_auditoria_usuario` (`ip`, `tiempo`, `usuario`, `proceso`) VALUES ('".$_SERVER['REMOTE_ADDR']."', NOW(), '".$_SESSION['usu_cedula']."', '$accion');");	
}
}



function conectar_bdd_auditoria(){

$this->auditar();	
$this->mysqli_auditar = new mysqli($this->servidores, $this->usuarios, $this->claves, $this->bdd);
if (!$this->mysqli_auditar) {
die('No se pudo conectar: ' . $this->mysqli_auditar->connect_error);
}else{
//echo "conecto exitosamente: <br>";
}}



// auditoria para todas las ejecuciones en la base de datos
function auditoria($sql)
{
// Nótese el uso de ===. Puesto que == simple no funcionará como se espera
// porque la posición de 'a' está en el 1° (primer) caracter.
$buscar = stristr($sql, 'select');
$buscar_mayus = stristr($sql, 'SELECT');
if($buscar === FALSE or $buscar_mayus === FALSE) {
$convertir = array("'" => "/", '"' => "/");
$accion =  strtr($sql, $convertir);
//$accion = addslashes($sql);

if (!isset($_SESSION['usu_login']))
{
$this->mysqli_auditar->query("INSERT INTO `auditoria` (`ip`, `tiempo`, `usuario`, `proceso`)	 VALUES ('".$_SERVER['REMOTE_ADDR']."', NOW(), USER(), '$accion');") ;
}else{
$this->mysqli_auditar->query("INSERT INTO `auditoria` (`ip`, `tiempo`, `usuario`, `proceso`)	 VALUES ('".$_SERVER['REMOTE_ADDR']."', NOW(), '".$_SESSION['usu_login']."', '$accion');");	
}
} 
}


// auditoria para las acciones que realizan los usuarios
function auditoria_usuarios($accion)
{
if (!isset($_SESSION['usu_login']))
{
$this->mysqli_auditar->query("INSERT INTO `auditoria_usuario` (`ip`, `tiempo`, `usuario`, `proceso`) VALUES ('".$_SERVER['REMOTE_ADDR']."', NOW(), USER(), '$accion');") ;
}else{
$this->mysqli_auditar->query("INSERT INTO `auditoria_usuario` (`ip`, `tiempo`, `usuario`, `proceso`) VALUES ('".$_SERVER['REMOTE_ADDR']."', NOW(), '".$_SESSION['usu_login']."', '$accion');");	
}
}
    
}

?>
