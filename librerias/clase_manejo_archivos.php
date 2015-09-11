<?php

/**
 * CLASE DE CARGAR DE ARCHIVOS
 *
 * En esta parte nos encargamos de crear un manejador de carpetas elimnar, mover, crear
 * 
 * @author MARLON ZAYRO ARIAS VARGAS
 * @version 1.0
 * @package clase
 * @category carga_archivos
 */
class manejo_carpetas {

  /**
   * 
   * @param type $ruta
   * @throws Exception
   */
  public function crear_carpeta($ruta) {

    if (!mkdir($ruta, 0777, true)) {

      throw new Exception("ocurrio un error al crear la carpeta");
    }
  }
  
  /**
   * 
   * @param type $ruta
   * @param type $destino
   * @throws Exception
   */
  public function mover_archivos($ruta, $destino) {
    if (!rename($ruta, $destino)) {
      throw new Exception("ocurrio un error al redireccionar archivo");
    }
  }

  /**
   * 
   * @param type $ruta_antigua
   * @param type $ruta_nueva
   * @throws Exception
   */
  public function renombrar_carpeta($ruta_antigua, $ruta_nueva) {

    if (!rename($ruta_antigua, $ruta_nueva)) {
      throw new Exception("ocurrio un error al redireccionar archivo");
    }
  }

  /**
   * 
   * @param type $archivo
   * @return boolean
   */
  public function eliminar_archivo($archivo) {
    if (!unlink($archivo)) {
      return "ocurrio un problema $archivo";
    } else {
      return true;
    }
  }

  /**
   * 
   * @param type $ruta
   */
  public function eliminar_carpeta($ruta) {
    if (is_dir($ruta)) {
      rmdir($ruta);
    } else {
      echo "no existe la ruta: " . $ruta;
    }
  }

  /**
   * 
   * @param type $carpeta
   */
  public function eliminar_contenido_carpeta($carpeta) {
    foreach (glob($carpeta . "/*") as $archivos_carpeta) {
#archivos a eliminar	
//echo $archivos_carpeta;

      if (is_dir($archivos_carpeta)) {
        eliminar_contenido_carpeta($archivos_carpeta);
      } else {
        unlink($archivos_carpeta);
      }
    }

    rmdir($carpeta);
  }

  /**
   * 
   * @param type $ruta
   * @return array
   */
  public function ver_carpeta($ruta) {
    
    $registros = array();
    $nombre = array();

// comprobamos si lo que nos pasan es un directorio

    if (is_dir($ruta)) {

// Abrimos el directorio y comprobamos que

      if ($aux = opendir($ruta)) {
        while (($archivo = readdir($aux)) !== false) {

// Si quisieramos mostrar todo el contenido del directorio pondríamos lo siguiente:
// echo '<br />' . $file . '<br />';
// Pero como lo que queremos es mostrar todos los archivos excepto "." y ".."




          if ($archivo != "." && $archivo != ".." && $archivo != ".htaccess") {

            $ruta_completa = $ruta . '/' . $archivo;

// Comprobamos si la ruta más file es un directorio (es decir, que file es
// un directorio), y si lo es, decimos que es un directorio y volvemos a
// llamar a la función de manera recursiva.

            if (is_dir($ruta_completa)) {
//echo "<br /><strong>Directorio:</strong> " . $ruta_completa;
//leer_archivos_y_directorios($ruta_completa . "/");
            } else {
#  echo '<br />' . $archivo . '<br />';
              $nombre['nombre'] = utf8_encode($archivo);
              $nombre['ruta'] = utf8_encode($ruta);
              array_push($registros, $nombre);

              $datos['archivos'] = $registros;
            }
          }
        }

        closedir($aux);

// Tiene que ser ruta y no ruta_completa por la recursividad
#   echo "<strong>Fin Directorio:</strong>" . $ruta . "<br /><br />";
      }
    } else {

      echo $ruta;
      echo "<br />No es ruta valida";
    }



    return $datos;
  }

}
