<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ofuscar
 *
 * @author Usuario
 */
class ofuscar {

public function ofuscar_archivo($ruta){


$gestor = fopen($ruta, "r");

$texto = base64_encode(gzdeflate(fread($gestor, file($file)),9));

fclose($gestor);

$gestos = fopen($file."_ofuscado","w");

fwrite($gestor, "<?php eval(grinflate(base64_decode($texto)))");

fclose($gestor);


}

}