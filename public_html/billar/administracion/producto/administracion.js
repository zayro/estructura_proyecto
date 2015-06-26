// JavaScript Document

/*
window.opener.$('#probando').val('gato');
window.opener.$('<li><a href="#pendiente" >Prueba</a></li>').toggleClass( "active" );
*/


$(function(){
	

$('#dg').edatagrid({
url: 'get.php',
saveUrl: 'save.php',
updateUrl: 'update.php',
destroyUrl: 'destroy.php'

});

});



function filtro(){
var dg = $('#dg');
dg.datagrid('enableFilter');    // enable filter
}

function eliminar(){
    var row = $('#dg').datagrid('getSelected');
    if (row){
        $.messager.confirm('Confirm','Desea Eliminar este registro? '+row.id,function(r){
            if (r){
                $.post('destroy.php',{id:row.id},function(result){
                    if (result.success){
                        $('#dg').datagrid('reload');    // reload the user data
                    } else {
                        $.messager.show({    // show error message
                            title: 'Error',
                            msg: result.errorMsg
                        });
                    }
                },'json');
            }
        });
    }
}


function actualizar()
{


//window.parent.myfunction(); 

window.opener.objeto.recargar_pagina('../../inicio.html');
$('#dg').datagrid({});
window.close();

}   

