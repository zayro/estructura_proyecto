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
controller: 'login'
}).
when('/entrada/', {
templateUrl: 'modulos/entrada/entrada.html',
controller: ''
}).
when('/ingreso/', {
templateUrl: 'modulos/ingreso/ingreso.html',
controller: 'ingreso'
}).        


// ruta por defecto
otherwise({
redirectTo: '/login/'
});
}]);


