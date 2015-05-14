<?php

include '../../../librerias/clase_consulta_bd.php';

$conexion = new consulta_bd();

$items = array();


/**
 * INCIA CICLO 1
 * CONSULTAMOS EL GRUPO
 */
$sql1 = "SELECT
grupo.id,
grupo.nombre as grupo	
FROM
grupo
WHERE grupo.id = '" . $_SESSION['grupo'] . "' ";
$resultado = $conexion->consulta($sql1);


while ($row = $resultado->fetch_object()) {


  /**
   * INCIA CICLO 2
   * CONSULTAMOS EL MENU
   */
  $sql2 = "SELECT		
menu.id as id_menu,
menu.nombre as menu
FROM
privilegio
LEFT JOIN grupo ON privilegio.id_grupo = grupo.id
LEFT JOIN menu ON menu.id = privilegio.id_menu
WHERE privilegio.id_grupo = '$row->id'";
  $resultado_2 = $conexion->consulta($sql2);
  $array_registros_2 = array();

  
  while ($row_2 = $resultado_2->fetch_object()) {

    /*
      foreach ($row_2 as $key => $valor) {
      # agrega dentro de un objeto un array
      # $array_registros[] = $valor;
      }
     */

    $array_registros_2[] = $row_2;

    $row->privilegio = $array_registros_2;


    /**
     * INCIA CICLO 3
     * CONSULTAMOS EL SUBMENU1
     */
    $sql3 = "SELECT
submenu_1.id  as id_submenu_1,
submenu_1.nombre as submenu,
submenu_1.modulo as modulo
FROM
menu 
JOIN submenu_1 ON submenu_1.id_menu = menu.id
WHERE id_menu = '$row_2->id_menu'";
    $resultado_3 = $conexion->consulta($sql3);
    $array_registros_3 = array();

    while ($row_3 = $resultado_3->fetch_object()) {

      $array_registros_3[] = $row_3;

      $row_2->submenu1 = $array_registros_3;


      /**
       * INCIA CICLO 2
       * CONSULTAMOS EL SUBMENU2
       */
      $sql4 = "SELECT
submenu_2.nombre AS submenu,
submenu_2.modulo as modulo
FROM
submenu_1 
JOIN submenu_2 ON submenu_1.id = submenu_2.id_submenu_1
WHERE submenu_2.id_submenu_1 = '$row_3->id_submenu_1' ";
      $resultado_4 = $conexion->consulta($sql4);
      $array_registros_4 = array();

      while ($row_4 = $resultado_4->fetch_object()) {
        $array_registros_4[] = $row_4;
        $row_3->submenu2 = $array_registros_4;
      }

      /*
       * ######## cierre de ciclo 4
       */
    }
    /*
     * ######## cierre de ciclo 3
     */
  }

  /*
   * ######## cierre de ciclo 2 
   */

  array_push($items, $row);
}

/*
 * ######## cierre de ciclo 1
 */

$result["registros"] = $items;


echo json_encode($result);



