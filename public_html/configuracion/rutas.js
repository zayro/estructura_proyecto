/*
 ###############################################
 INICIA RUTAS
 ###############################################
 */

app.config(['$routeProvider',
  function ($routeProvider) {
    $routeProvider.
            when('/login/', {
              templateUrl: 'modulos/logueo/login.html',
              controller: 'valida_usuario'
            }).
            when('/modulo/:modulo', {
              templateUrl: function (routeParams) {
                return 'modulos/' + routeParams.modulo + '/' + routeParams.modulo + '.html';
              },
              controller: 'valida_usuario'
 }).
// ruta por defecto
            otherwise({
              redirectTo: '/login/'
            });
  }]);


