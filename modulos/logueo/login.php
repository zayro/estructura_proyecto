<?php

include('../../librerias/clase_login.php');

$objeto = new login();


extract($_REQUEST);
echo $objeto->logueo($usuario, $clave);


?>