<?php

/**
 * INTERFAZ AUDITORIA
 * 
 * OBLIGA A UTILIZAR ESTOS 1 METODOS DENTRO DE LA SUBCLASE
 *
 * se utiliza para la auditoria
 * 
 * @author MARLON ZAYRO ARIAS VARGAS
 * @version 1.0
 * @package interfaz
 * @category auditoria
 */
interface auditar {

    function auditoria($sql, $mensaje);

    
}

?>