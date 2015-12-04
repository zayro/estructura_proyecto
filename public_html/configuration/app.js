/*
 ###############################################
 INICIA APP
 ###############################################
 */

"use strict";

var app = angular.module('app', ['ngRoute', 'ngSanitize', 'ngLocale', 'ngTouch', 'aplicativo_billar', 'aplicativo_parqueadero']);


angular.element(document).ready(function () {

  console.log('cargo angular');

});


/*
 ###############################################
 INICIA EL SERVICIO
 ###############################################
 */

app.factory('webservicio', ['$q', '$rootScope', function ($q, $rootScope) {
    // We return this object to anything injecting our service
    var Service = {};
    // Keep all pending requests here until they get responses
    var callbacks = {};
    // Create a unique callback ID to map requests to responses
    var currentCallbackId = 0;
    // Create our websocket object with the address to the websocket
    var ws = new WebSocket("ws://172.21.10.38:9300");

    ws.onopen = function () {
      console.log("Socket has been opened!");
    };

    ws.onmessage = function (message) {
      listener(JSON.parse(message.data));
    };

    function sendRequest(request) {
      var defer = $q.defer();
      var callbackId = getCallbackId();
      callbacks[callbackId] = {
        time: new Date(),
        cb: defer
      };
      request.callback_id = callbackId;
      console.log('Sending request', request);
      ws.send(JSON.stringify(request));
      return defer.promise;
    }

    function listener(data) {
      var messageObj = data;
      console.log("Received data from websocket: ", messageObj);
      // If an object exists with callback_id in our callbacks object, resolve it
      if (callbacks.hasOwnProperty(messageObj.callback_id)) {
        console.log(callbacks[messageObj.callback_id]);
        $rootScope.$apply(callbacks[messageObj.callback_id].cb.resolve(messageObj.data));
        delete callbacks[messageObj.callbackID];
      }
    }
    // This creates a new callback ID for a request
    function getCallbackId() {
      currentCallbackId += 1;
      if (currentCallbackId > 10000) {
        currentCallbackId = 0;
      }
      return currentCallbackId;
    }

    // Define a "getter" for getting customer data
    Service.getCustomers = function () {
      var request = {
        type: "get_customers"
      }
      // Storing in a variable for clarity on what sendRequest returns
      var promise = sendRequest(request);
      return promise;
    }

    return Service;
  }]);

app.factory('websocket', function ($websocket) {
  // Open a WebSocket connection
  var dataStream = $websocket('ws://172.21.10.38:9300');

  var collection = [];

  dataStream.onMessage(function (message) {
    console.log(message);
    collection.push(JSON.parse(message.data));
  });

  var methods = {
    collection: collection,
    get: function () {
      //dataStream.send(JSON.stringify({ action: 'get' }));
      dataStream.send("enviando");
    }
  };

  return methods;
});

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
    return $http.get('controller/controller_menu.php?mostrar_menu=true');
  };

  this.select_combo_empresas = function () {
    return $http.get('controller/controller_empresa.php?mostrar_empresas=true');
  };

  this.session_usuario = function () {
    return $http.get('../librerias/session_usuario.php');
  };

});

app.service('socket', function () {
   
    
  try {
    var socket = io.connect('http://'+myip+':1234');

    this.zocalo = socket;

  } catch (mensaje) {

    console.error('ocurrion un problemna: ' + mensaje);

  }

});

/*
 ###############################################
 INICIA RUTAS
 ###############################################
 */

app.config(['$routeProvider', '$locationProvider', function ($routeProvider, $locationProvider) {

    // wsProvider.setUrl('ws://172.21.10.38:9300');

    $routeProvider.
            when('/login', {
              templateUrl: 'view/logueo/login.html',
              controller: 'valida_usuario'
            }).
            when('/principal', {
              templateUrl: 'view/ingreso/ingreso.html',
              controller: 'valida_usuario'
            }).
            when('/view/:view', {
              templateUrl: function (routeParams) {
                return 'view/' + routeParams.view + '/' + routeParams.view + '.html';
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

            })
            .otherwise({redirectTo: '/login'});



    //check browser support
    if (window.history && window.history.pushState) {

      // to know more about setting base URL visit: https://docs.angularjs.org/error/$location/nobase

      // if you don't wish to set base URL then use this
      $locationProvider.html5Mode({
        enabled: true,
        requireBase: true
      });
    }

  }]);

/*
 ###############################################
 INICIA EL CONTROLADOR APLICACIONES
 ###############################################
 */


app.controller('AppCtrl', function ($scope, $route, $routeParams, $location, $log, $http, cargar_servicios) {
//function AppCtrl($scope, $route, $routeParams, $location, $log, $http, cargar_servicios) {



  /*
   ###############################################
   INICIA EL SOCKET
   ###############################################
   
   
   ws.on('message', function (event) {
   $log.info('New message', event.data);
   });
   
   ws.baseSocket.onmessage = function (event) {
   $log.debug('Nuevo mensaje', event.data);
   }
   
   ws.send('custom message');
   
   
   var Server;
   
   $scope.log = function (text) {
   console.log(text);
   }
   
   $scope.send = function (text) {
   Server.send('message', text);
   }
   
   
   $scope.log('Connecting...');
   Server = new FancyWebSocket('ws://172.21.10.38:9300');
   
   //Let the user know we're connected
   Server.bind('open', function () {
   $scope.log("Connected.");
   });
   
   //OH NOES! Disconnection occurred.
   Server.bind('close', function (data) {
   $scope.log("Disconnected.");
   });
   
   //Log any messages sent from server
   Server.bind('message', function (payload) {
   $scope.log(payload);
   });
   
   Server.connect();  
   */


  angular.element(document).ready(function () {
    //$('#carga_inicial').openModal();
    console.info("cargo controlador principal");

    $('.dropdown-button').dropdown({
      inDuration: 300,
      outDuration: 225,
      constrain_width: true, // Does not change width of dropdown to that of the activator
      hover: false, // Activate on hover
      gutter: 0, // Spacing from edge
      belowOrigin: true, // Displays dropdown below the button
      alignment: 'right' // Displays dropdown with edge aligned to the left of button
    }
    );




    /*
     ###############################################
     CONFIGURACION DEL TEMA
     ###############################################
     */

    $scope.$route = $route;
    $scope.$location = $location;
    $scope.$routeParams = $routeParams;

    if (localStorage.getItem("tema") === null) {
      var tema = {'color_menu': 'blue-grey darken-4', 'color_sidebar': 'blue-grey darken-3'};
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

  });

  $scope.terminar_session = function (identificacion) {
    $http.get('controller/controller_login.php?salir=true&identificacion=' + identificacion).success(function (respuesta) {
      console.debug('salir del sistema', respuesta);
      $location.path('/login');
      $route.reload();
    });
  };


  // funcion se ejecuta cada vez que ingreso algun modulo
  $scope.verificar_session = function () {


    cargar_servicios.session_usuario().success(function (data) {

      if (data.session == false && $location.path() != '/login') {
        console.info("se elimino session_sistema");
        localStorage.removeItem('session_sistema');
        window.location = "login";
        //$location.path('/login');
      }

      $scope.select_session_usuario = data;
      console.log(data);
      var identificacion = $scope.select_session_usuario.identificacion;
      var storage = localStorage.getItem("session_sistema");
      var datos_session = JSON.parse(storage);


      var valida_modulo = $.ajax({
        url: "view/logueo/select_permisos.php",
        method: "post",
        data: {modulo_actual: '#' + $location.path()},
        dataType: "json",
        beforeSend: function () {
          console.log('se enviaran los datos para verificar modulo');

        }
      });


      valida_modulo.done(function (data) {
        if (data.registros_encontrado == 0) {
          // verifica los permisos de ingreso al modulo
          window.location = "login";
        }
      });

      valida_modulo.fail(function (jqXHR, textStatus) {
        console.error("Error: ");
        console.error(textStatus);
        console.error(jqXHR);
      });


      if (typeof identificacion === 'undefined' || datos_session.empresa != $scope.select_session_usuario.empresa) {
        $scope.ocultar_menu();
        // window.location = "#/login/";

      } else {
        $scope.ocultar_menu();

        cargar_servicios.select_menu().success(function (data) {
          $scope.menu_logueo = data.registros;
          console.info("MENU : %O ", data.registros);
        });
      }

    });




    $scope.cerrar_modal_conectado = function () {
      $('#usuario_conectado').closeModal();
    };

    $scope.ocultar_menu = function () {
      $('.button-collapse').sideNav('hide');
      $('#modal_menu').closeModal();
    };

    $scope.mostrar_menu = function () {
      $('.button-collapse').sideNav('show');
      $('#modal_menu').openModal();
    };

    $scope.mostrar_sidebar = function () {
      $('.button-collapse').sideNav('show');
    };

    $scope.ocultar_sidebar = function () {
      $('.button-collapse').sideNav('hide');
    };

  };

// se recibe la comprobacion del login
  $scope.$on("update_parent_controller", function (event, message) {
    $scope.verificar_session();
    console.log('trigger update_parent_controller AppCtrl');
  });


  $scope.cambiar_color_menu = function (valor) {
     
    var tema = {
      'color_menu': valor,
      'color_sidebar': 'blue-grey darken-3'
    };

    localStorage.setItem('tema', JSON.stringify(tema));
    var storage_tema = localStorage.getItem("tema");
    var datos_tema = JSON.parse(storage_tema);
    console.debug(datos_tema);

    $scope.color_menu = datos_tema.color_menu;
    
    $route.reload();
    

  };

  $scope.cambiar_color_sidebar = function (valor) {
    var tema = {
      'color_menu': 'blue-grey darken-4',
      'color_sidebar': valor
    };

    localStorage.setItem('tema', JSON.stringify(tema));
    var storage_tema = localStorage.getItem("tema");
    var datos_tema = JSON.parse(storage_tema);
    console.debug(datos_tema);

    $scope.color_sidebar = datos_tema.color_sidebar;  
    
    $route.reload();
  };
  
    /* 
  $scope.$watch('color_menu', function (newValue, oldValue) {
    console.debug('$watch: color_menu', newValue);
  });

  $scope.$watch('color_sidebar', function (newValue, oldValue) {
    console.debug('$watch: color_sidebar', newValue);
  });
  */

});

/*
 ##################################################
 INICIA EL CONTROLADOR QUE VALIDA SI EXISTE SESSION
 ###################################################
 */

app.controller('valida_usuario', function ($scope, $route, cargar_servicios) {
//function valida_usuario($scope, cargar_servicios) {

  console.groupCollapsed("ingreso al controlador valida usuario");


  $('#fp-nav').remove();
  $('html, body').removeAttr('style');

  // al controlador principal le digo que revice  si existe una session vigente
  $scope.$emit('update_parent_controller', 'validando el usuario');

  console.groupEnd();
});

