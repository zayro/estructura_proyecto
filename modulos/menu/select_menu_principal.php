<?php

include '../../librerias/clase_consulta_bd.php';

$objeto = new consulta_bd();

try {

  $verificador = $objeto->json_bd("SELECT
	grupo.nombre as grupo,
	menu.nombre as menu,
	submenu_1.nombre AS submenu1,
	submenu_1.modulo as modulo1,
	submenu_2.nombre AS submenu2,
	submenu_2.modulo as modulo2
FROM
	privilegio
LEFT JOIN grupo ON privilegio.id_grupo = grupo.id
LEFT JOIN menu ON menu.id = privilegio.id_menu
LEFT JOIN submenu_1 ON submenu_1.id = menu.id 
LEFT JOIN submenu_2 ON submenu_1.id = submenu_2.id_submenu_1
WHERE grupo.id = 4;
");



} catch (Exception $e) {

  $verificador = $e->getMessage();
}
$objeto->imprime_json($verificador);



