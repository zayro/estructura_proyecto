/*
 ###############################################
 INICIA APP
 ###############################################
 */

"use strict";

var app = angular.module('app', ['ngRoute', 'ngSanitize', 'ngLocale', 'ngTouch', 'aplicativo_billar', 'aplicativo_parqueadero']);


angular.element(document).ready(function () {

  console.info('cargo angular');

});

/*
 ###############################################
 INICIA EL SERVICIO
 ###############################################
 */

app.service('cargar_servicios', function ($http) {

  this.http_respuesta = function (valor_url, valor_metodo, valor_formulario) {

    console.log('url: ' + valor_url + ' datos: ' + valor_formulario);

    return $http({
      method: valor_metodo,
      url: valor_url,
      data: valor_formulario,
      headers: {'Content-Type': 'application/x-www-form-urlencoded charset=UTF-8'}
    });

  };

  this.set_validar_session = function (valor) {
    this.datos_ingreso = valor;
  };

  this.validar_session = function () {
    return this.datos_ingreso;
  };

  this.select_menu = function () {
    return $http.get('modulos/menu/select_menu.php');
  };

  this.select_combo_empresas = function () {
    return $http.get('modulos/logueo/combo_empresas.php');
  };



});

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
            when('/modulo/ingreso/', {
              templateUrl: 'modulos/ingreso/ingreso.html',
              controller: 'valida_usuario'
            }).
            when('/modulos/:modulo', {
              templateUrl: function (routeParams) {
                return 'modulos/' + routeParams.modulo + '/' + routeParams.modulo + '.html';
              },
              controller: 'valida_usuario',
              resolve: {
                // I will cause a 1 second delay
                delay: function ($q, $timeout) {
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

/*
 ###############################################
 INICIA DIRECTIVA
 ###############################################
 */

app.directive("fragmentoEntrada", function () {
  return {
    restrict: 'E',
    templateUrl: "modulos/logueo/entrada.html",
    link: function (scope, elemento, atributos) {
    }
//controller: 'controlador'
  };
});
/*
 ###############################################
 INICIA EL CONTROLADOR APLICACIONES
 ###############################################
 */

app.controller('AppCtrl', function ($scope, $route, $routeParams, $location, $log, $http, cargar_servicios) {


  $scope.$route = $route;
  $scope.$location = $location;
  $scope.$routeParams = $routeParams;


// Register the callback to be fired every time auth state changes
//  var ref = new Firebase("https://estructuraproyecto.firebaseio.com");
  /*
   ###############################################
   CONFIGURACION DEL TEMA
   ###############################################
   */

  angular.element(document).ready(function () {
    //$('#carga_inicial').openModal();
    console.info("cargo controlador principal");
  });

  if (localStorage.getItem("tema") === null) {
    var tema = {'color_menu': 'blue-grey darken-4', 'color_sidebar': 'grey darken-3'};
    localStorage.setItem('tema', JSON.stringify(tema));
  }

  var storage_tema = localStorage.getItem("tema");
  var datos_tema = JSON.parse(storage_tema);
  $scope.color_menu = datos_tema.color_menu;
  $scope.color_sidebar = datos_tema.color_sidebar;



  $scope.$on('$locationChangeStart', function (event) {
    console.warn("se recargo el navegador");
    console.clear();
    /*var answer = confirm("Desea salir del sistema?");
     if (!answer) {
     event.preventDefault();
     }
     */
  });


  // funcion se ejecuta cada vez que ingreso algun modulo
  $scope.verificar_session = function () {
    

    var request = $.ajax({
      url: "../librerias/session_usuario.php",
      method: "get",
      dataType: "json",
      beforeSend: function () {
        console.log('se enviaran los datos para verificar la sesion');
      }
    });

    request.done(function (data) {
      
          
      if(data.session){ 
        console.info("se elimino session_sistema");
        localStorage.removeItem('session_sistema');
        window.location = "#/login/";
        }

      $scope.select_session_usuario = data;

      var identificacion = $scope.select_session_usuario.identificacion;
      var storage = localStorage.getItem("session_sistema");
      var datos_session = JSON.parse(storage);
      
      console.log("locacion",'#' + $location.path());

      var valida_modulo = $.ajax({
        url: "modulos/logueo/select_permisos.php",
        method: "post",
        data: {modulo_actual: '#' + $location.path()},
        dataType: "json",
        beforeSend: function () {
          console.log('se enviaran los datos para verificar modulo');
        }
      });

      console.log('#' + $location.path());      

      valida_modulo.done(function (data) {
        if (data.registros_encontrado == 0) {
          // verifica los permisos de ingreso al modulo
          window.location = "#/login/";
        }
      });

      valida_modulo.fail(function (jqXHR, textStatus) {
        console.error("Error: ");
        console.error(textStatus);
        console.error(jqXHR);
      });
    
      
      if (  typeof identificacion === 'undefined' ||  datos_session.empresa != $scope.select_session_usuario.empresa  ) {

        window.location = "#/login/";

      } else {
        $scope.ocultar_menu();

        cargar_servicios.select_menu().success(function (data) {
          $scope.menu_logueo = data.registros;
          console.info("MENU : %O ", data.registros);
        });
      }
      
    });

    request.fail(function (jqXHR, textStatus) {
      console.error("Error: ");
      console.error(textStatus);
      console.error(jqXHR);
      cargar_servicios.set_validar_session(jqXHR);
    });

    $scope.ocultar_menu = function () {
      $('.button-collapse').sideNav('hide');
      $('#modal_menu').closeModal();
    };

    $scope.mostrar_menu = function () {
      $('.button-collapse').sideNav('show');
      $('#modal_menu').openModal();
    };
    
    $scope.mostrar_sidebar= function () {
      $('.button-collapse').sideNav('show');      
    };
    
        $scope.ocultar_sidebar= function () {
      $('.button-collapse').sideNav('hide');
    };

  };

// se recibe la comprobacion del login
  $scope.$on("update_parent_controller", function (event, message) {
    $scope.mensaje = message;
    $scope.verificar_session();
    console.log('trigger update_parent_controller AppCtrl');
  });


});

/*
 ##################################################
 INICIA EL CONTROLADOR QUE VALIDA SI EXISTE SESSION
 ###################################################
 */

app.controller('valida_usuario', function ($scope, cargar_servicios) {

  console.groupCollapsed("ingreso al controlador valida usuario");
  
  
  $('#fp-nav').remove();
  $('html, body').removeAttr('style');
  


  // al controlador principal le digo que revice  si existe una session vigente
  $scope.$emit('update_parent_controller', 'valida_ingreso');

  console.groupEnd();
});

/*
 ###############################################
 CONTROLADOR INGRESO
 ###############################################
 */

app.controller('ingreso', function ($scope, cargar_servicios) {



  $scope.enviar_formulario = function (datos, valor_url) {
    console.group("ingreso al controlador ingreso");

    var valor_metodo = "POST";
    var valor_datos = $('#' + datos).serialize();

    cargar_servicios.http_respuesta(valor_url, valor_metodo, valor_datos)

            .success(function (msg) {

              if (msg.success) {

                Materialize.toast("Exitoso" + "<span class='btn-flat green-text' >" + msg.suceso + "</span>", 4000);
                //setTimeout("window.print()" , 5000);

              } else {
                Materialize.toast("Error" + "<a class='btn-flat red-text'  >X<a>", 5000);
                Materialize.toast(msg.suceso + "<a class='btn-flat red-text'  >X<a>", 5000);
              }

              //$('#'+id_formulario).trigger("reset");

            })

            .error(function (data, status, headers, config) {
              console.error(data);
            });
    console.groupEnd();

  };



});

/*
 ###############################################
 CONTROLADOR LOGIN
 ###############################################
 */

app.controller('login', function ($scope, cargar_servicios) {

  console.info("ingreso al controlador login");

  cargar_servicios.select_combo_empresas().success(function (data) {
    $scope.combo_empresas = data;
  });

  $scope.enviar_formulario_login = function () {
    var valor_url = "modulos/logueo/login.php";
    var valor_metodo = "POST";
    var valor_datos = $('#formulario_logueo').serialize();

    cargar_servicios.http_respuesta(valor_url, valor_metodo, valor_datos)

            .success(function (msg) {


              localStorage.setItem('session_sistema', JSON.stringify(msg));

              if (msg.success)
              {
                //console.clear();
                console.log("ingreso al sistema");
                // remueve el menu inferio del fullpage
                $('#fp-nav').remove();
                // remueve el efecto overflow de fullpage
                $("html, body").removeAttr('style');
                $("html, body").css("overflow", "auto");
                // redigie al ingreso
                window.location = '#modulo/ingreso/';
                Materialize.toast("Ingreso Exitoso" + "<span class='btn-flat green-text' >" + msg.usuario + "</span>", 4000);
              }
              else
              {
                Materialize.toast("VERIFICAR LOS DATOS" + "<a class='btn-flat red-text' > Error <a>", 4000);
              }

              //$('#'+id_formulario).trigger("reset");

            })

            .error(function (data, status, headers, config) {
              console.error(data);
              console.error(status);
              console.error(headers);
              console.error(config);
            });

  };

  $scope.enviar_formulario_cambio_clave = function (modal) {
    var valor_url = "modulos/logueo/cambio_clave.php";
    var valor_metodo = "POST";
    var valor_datos = $('#formulario_cambio_clave').serialize();

    cargar_servicios.http_respuesta(valor_url, valor_metodo, valor_datos)

            .success(function (msg) {

              if (msg.success) {


                Materialize.toast("SE CAMBIO CLAVE" + "<span class='btn-flat green-text' >" + msg.suceso + "</span>", 4000);
                $('#' + modal).closeModal();

              } else {
                Materialize.toast("VERIFICAR LOS DATOS" + "<a class='btn-flat red-text'  > Error<a>", 4000);

              }

              //$('#'+id_formulario).trigger("reset");

            })

            .error(function (data, status, headers, config) {
              console.error(data);
              console.error(status);
              console.error(headers);
              console.error(config);
            });

  };

  $scope.enviar_formulario_recordar_clave = function ($valor) {

    var valor_url = "modulos/logueo/validar_usuario.php";
    var valor_metodo = "POST";
    var valor_datos = $('#formulario_recordar_clave').serialize();

    cargar_servicios.http_respuesta(valor_url, valor_metodo, valor_datos)

            .success(function (msg) {

              if (!msg.success) {


                Materialize.toast("ENVIANDO MENSAJE ..." + "<span class='btn-flat green-text' >" + msg.suceso + "</span>", 4000);


              } else {
                Materialize.toast("VERIFICAR LOS DATOS" + "<a class='btn-flat red-text'  > Error<a>", 4000);

              }

              //$('#'+id_formulario).trigger("reset");

            })

            .error(function (data, status, headers, config) {
              console.error(data);
              console.error(status);
              console.error(headers);
              console.error(config);
            });
  }

});