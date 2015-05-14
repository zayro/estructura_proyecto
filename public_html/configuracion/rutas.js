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
controller: 'ingreso'
}).

when('/ingreso/', {
templateUrl: 'modulos/ingreso/ingreso.html',
controller: 'ingreso'
}).        


when('/informe_movimientos/', {
templateUrl: 'modulos/informes/informe_movimientos.html',
controller: 'ctr_informe_movimientos'
}).        

when('/informe_pendientes/', {
templateUrl: 'modulos/informes/informe_pendientes.html',
controller: 'ctr_informe_pendientes'
}).

// ruta por defecto
otherwise({
redirectTo: '/login/'
});
}]);


