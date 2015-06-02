/*
###############################################
INICIA RUTAS
###############################################
*/

app.config(['$routeProvider',
function($routeProvider) {
$routeProvider.
when('/login/', {
templateUrl: 'modulos/logueo/login.html',
controller: 'valida_usuario'
}).

when('/ingreso/', {
templateUrl: 'modulos/ingreso/ingreso.html',
controller: 'valida_usuario'
}).        



// ruta por defecto
otherwise({
redirectTo: '/login/'
});
}]);


