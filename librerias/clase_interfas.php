<?php

/*
 * OBLIGA A UTILIZAR ESTOS 2 METODOS DENTRO DE LA SUBCLASE
 *
 *
 */

interface auditoria {

    function auditoria_usuario($mensaje);

    function auditoria_privada($sql);
}

?>