<?php

include '../../../librerias/clase_consulta_bd.php';
$objeto = new consulta_bd();
extract($_REQUEST);


 
if(isset($_SESSION['grupo']) and isset($_SESSION['identificacion']) and isset($modulo_actual) ){
  
  $result = array();
$consulta = "SELECT
menu.id AS id_menu,
menu.nombre AS menu,
submenu_1.nombre AS submenu1,
submenu_1.modulo AS modulo1,
submenu_2.nombre AS submenu2,
submenu_2.modulo AS modulo2
FROM
privilegio
LEFT JOIN grupo ON privilegio.id_grupo = grupo.id
JOIN usuarios AS u ON u.grupo = grupo.id
LEFT JOIN menu ON menu.id = privilegio.id_menu
LEFT JOIN submenu_1 ON submenu_1.id_menu = menu.id
LEFT JOIN submenu_2 ON submenu_1.id = submenu_2.id_submenu_1
WHERE
grupo.id = '".$_SESSION['grupo']."'
AND u.identificacion = '".$_SESSION['identificacion']."'
AND (
submenu_1.modulo = '$modulo_actual'
OR submenu_2.modulo = '$modulo_actual')";
 
$result[] = $objeto->consulta_json($consulta);
$result['success'] = true;
$result['sql'] = $consulta;

}else{
$result['success'] = false;


  
}

echo json_encode($result);
?>