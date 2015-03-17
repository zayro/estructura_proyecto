/*
###############################################
INICIA RUTAS
###############################################
*/

app.config(['$routeProvider',
function($routeProvider) {
$routeProvider.
when('/listado/', {
templateUrl: 'modulos/listados/index.html',
controller: ''
}).
when('/formulario/', {
templateUrl: 'modulos/formularios/index.html',
controller: ''
}).


// ruta por defecto
otherwise({
redirectTo: '/'
});
}]);


