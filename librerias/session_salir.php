<?php
# "se elimino la session y el usuario enlinea";

require('clase_login.php');

$objeto = new login();
extract($_REQUEST);
session_start();
session_destroy();

$objeto->eliminar_conectado($identificacion);



header('Location: ../public_html/index.html');


exit();  
?>


