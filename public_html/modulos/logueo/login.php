<?php

require('../../../librerias/clase_login.php');

$objeto = new login();

if(extract($_REQUEST)){
echo $objeto->logueo($usuario, $clave);
}else{
echo "NO SE RECIBIERON DATOS";
}

?>