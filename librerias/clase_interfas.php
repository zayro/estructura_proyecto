<?php

/**
 * INTERFAZ AUDITORIA
 * 
 * OBLIGA A UTILIZAR ESTOS 2 METODOS DENTRO DE LA SUBCLASE
 *
 * se utiliza para la auditoria
 * 
 * @author MARLON ZAYRO ARIAS VARGAS
 * @version 1.0
 * @package interfaz
 * @category auditoria
 */
interface auditoria {

    function auditoria_usuario($mensaje);

    function auditoria_privada($sql);
}

?>