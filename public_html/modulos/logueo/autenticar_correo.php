<?php

include('../../../librerias/clase_login.php');

$objeto = new login();

extract($_REQUEST);

echo json_encode($objeto->autenticar_correo($usuario, $clave));



?>