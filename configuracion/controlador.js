/*
###############################################
INICIA EL CONTROLADOR
###############################################
*/
app.controller('ingreso', function($scope, $timeout,  $log, cargar_servicios) {

cargar_servicios.select_session().success(function(data){
$log.log(data.identificacion);		
if(data.identificacion == "" || data.identificacion == undefined ){ 
window.location.href = 'index.html';
}
});	
});


app.controller('controlador', function($scope, $http, cargar_registros) {

$scope.prueba =  "texto";

$scope.mostrar_consulta = 


cargar_registros.traer_consultas()
.success(function(data){
$scope.resultados = data.registros;
});

$scope.jquery = function(){

$.ajax({
type: "POST",
url: "some.php",
data: { name: "John", location: "Boston" }
})
.done(function( msg ) {
alert( "Data Saved: " + msg );
}) 
.fail(function( jqXHR, textStatus ) {
alert( "Request failed: " + textStatus );
});

}

$scope.formularios =  function (){
var valor_url = url_formulario;
var valor_metodo = metodo_formulario;
var valor_datos = $('#'+id_formulario).serialize();

cargar_registros.respuesta_registros(valor_url, valor_metodo, valor_datos)

.success(function(data){

//$('#'+id_formulario).trigger("reset");



})

.error(function(data, status, headers, config) {
console.error(data);
});

}


});

/*
###############################################
TERMINA EL CONTROLADOR
###############################################
*/


// CONTROL DE LAS APLICACIONES
app.controller('AppCtrl', function($scope, $timeout,  $log) {




$scope.ocultar_menu = function (){
	
$('.button-collapse').sideNav('hide');	
	}


$scope.mostrar_menu = function (){
	
$('.button-collapse').sideNav('show');	
	}


/*
$timeout(function() {
$mdSidenav('left').toggle();
console.log('Esperamos 3 segundos y ejecutamos el menu')
}, 3000);
*/



});
