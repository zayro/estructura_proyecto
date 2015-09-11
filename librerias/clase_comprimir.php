<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace comprimir;

/**
 * Description of clase_comprimir
 *
 * @author Usuario
 */
class comprimir extends ZipArchive {

  public function comprimir_archivo($archivo_comprimir, $guardar_comprimido) {

//$filename = 'copia/reconocer_'.date("Y_m_d").'.zip';

    if ($this->open($archivo_comprimir, ZIPARCHIVE::CREATE) === true) {
      $this->addFile($guardar_comprimido);
    } else {
      echo 'Error creando ' . $archivo_comprimir;
    }
    echo "numficheros: " . $this->numFiles . "\n";
    echo "estado:" . $this->status . "\n";
    $this->close();
  }

  public function comprimir_directorio($path) {
    print 'adding ' . $path . '<br>';
    $this->addEmptyDir($path);
    $nodes = glob($path . '/*');
    foreach ($nodes as $node) {
      print $node . '<br>';
      if (is_dir($node)) {
        $this->addDir($node);
      } else if (is_file($node)) {
        $this->addFile($node);
      }
    }
  }

}
