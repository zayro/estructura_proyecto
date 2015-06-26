// JavaScript Document debugger;

$.post( "../../../libreria_php/ejecutar_consulta.php", { consulta: "select * from usuarios where estado = 1 and correo = '"+localStorage.correo+"'" },

function( data ) {


if( Number(localStorage.estado) != 1 | Number(data.registros) != 1){

alert(data.registros);

location.href = "../../index.html";



}else{  console.info("permisos de ingreso"); }

},  "json")

.done(function( data ) {
console.log( "Data Loaded: " + data );
})

.fail(function( data ) {
console.log( data );
});