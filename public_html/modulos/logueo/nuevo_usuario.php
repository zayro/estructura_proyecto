<?php

include('../../../librerias/clase_login.php');

$objeto = new login();

extract($_REQUEST);

echo json_encode($objeto->usuario_nuevo($usuario, $clave, $correo, $grupo, $identificacion));



?>
