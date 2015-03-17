/*
###############################################
INICIA EL SERVICIO
###############################################
*/




app.service('cargar_servicios', function($http) {

this.respuesta_servicios = function(valor_url, valor_metodo, valor_formulario) {

console.log('url: '+valor_url+' datos: '+valor_formulario);


return $http({
method : valor_metodo,
url : valor_url,
data : valor_formulario,
headers : { 'Content-Type': 'application/x-www-form-urlencoded charset=UTF-8' }
});

};





this.select_session = function (){
return $http.get('librerias/session_usuario.php');
};



});