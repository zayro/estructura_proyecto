/*
 ###############################################
 INICIA RUTAS
 ###############################################
 */

app.config(['$routeProvider',
  function ($routeProvider, $locationProvider) {
    $routeProvider.
            when('/login/', {
              templateUrl: 'modulos/logueo/login.html',
              controller: 'valida_usuario'
            }).
            when('/modulos/:modulo', {
              templateUrl: function (routeParams) {
                return 'modulos/' + routeParams.modulo + '/' + routeParams.modulo + '.html';
              },
              controller: 'valida_usuario',
                resolve: {
      // I will cause a 1 second delay
      delay: function($q, $timeout) {
        var delay = $q.defer();
        $timeout(delay.resolve, 1000);
        return delay.promise;
      }
    }
  
 }).
// ruta por defecto
            otherwise({
              redirectTo: '/login/'
            });
  }]);


