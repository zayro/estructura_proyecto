<?php  session_start();
/*
ini_set("session.cookie_lifetime","7200");
ini_set("session.gc_maxlifetime","7200");
date_default_timezone_set( 'America/Bogota' );
ini_set ('display_errors', 1);
set_time_limit (0);
*/

/**
* CLASE DE CONEXION PRINCIPAL
*
* En esta parte nos encargamos de crear los tipos de conexion del proyecto 
* para poder asi administrar los tipos de permisos de acceso
*
* @author MARLON ZAYRO ARIAS VARGAS
* @param string $mysqli Se empleara la conexion mysqli que es orientada a objetos
* @return returna si la conexion fue exitosa
* @version 1.0
* @package clase\conexion
* @copyright 2015 
*/

class conexion {


const VERSION = '1.0';
const FECHA_DE_APROBACION = '2015-05-02';


protected $mysqli;



/**
* METODO CONECTAR
*
* esperamos los  parametro de conexion
*
*/
protected function conectar(){

$this->mysqli = new mysqli($this->servidores, $this->usuarios, $this->claves, $this->bdd);

if (!$this->mysqli) {   
return ('No se pudo conectar: ' . $this->mysqli->connect_error); 

}else{ 

$this->mysqli->query("SET NAMES 'utf8'");

return "conecto exitosamente: <br>"; 

} 

}


protected function local(){
$this->servidores = 'localhost';
$this->usuarios = 'root';
$this->claves = 'zayro';
$this->bdd = 'transito';
}




}


/*
* OBLIGA A UTILIZAR ESTOS 2 METODOS DENTRO DE LA SUBCLASE
*
*
*/

interface auditoria {

function usuario($mensaje);

function privada($sql);

}

/*
* NOS PROTEGE LOS METODOS PARA NO SER UTILIZADOS EN LA SUBCLASE
*
*
*/
abstract class Operacion {
}


