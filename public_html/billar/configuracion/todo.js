/*
 ###############################################
 INICIA EL MODULO
 ###############################################
 */


var app = angular.module('aplicativo_billar', []);

/*
 ###############################################
 INICIA RUTAS
 ###############################################
 */

app.config(['$routeProvider',
  function ($routeProvider) {
    $routeProvider.
            when('/informe_consumo/', {
              templateUrl: 'modulos/informe_consumo.html',
              controller: 'valida_usuario'
            }).
            when('/informe_tiempo/', {
              templateUrl: 'modulos/informe_tiempo.html',
              controller: 'valida_usuario'
            }).
            when('/billar/modulos/panel_consumos/', {
              templateUrl: 'billar/modulos/panel_consumos.html',
              controller: 'valida_usuario'
            }).
            when('/billar/modulos/panel_ubicacion/', {
              templateUrl: 'billar/modulos/panel_ubicacion.html',
              controller: 'valida_usuario'
            }).
            when('/billar/modulos/panel_pago/', {
              templateUrl: 'billar/modulos/panel_pago.html',
              controller: 'valida_usuario'
            });



  }]);



/*
 ###############################################
 INICIA EL SERVICIO
 ###############################################
 */


app.service('cargar_registros', function ($http) {

  this.respuesta_registros = function (valor_url, valor_metodo, valor_formulario) {

    console.log('url: ' + valor_url + ' datos: ' + valor_formulario);

    return $http({
      method: valor_metodo,
      url: valor_url,
      data: valor_formulario,
      headers: {'Content-Type': 'application/x-www-form-urlencoded charset=UTF-8'}
    });

  };


  this.mesas_disponibles = function () {

    return $http.get('billar/script_php/seleccionar_disponible.php');

  };


  this.tabla_ubicacion = function () {

    return $http.get('billar/script_php/combo_id_ubicacion.php');

  };


  this.tabla_servicio = function () {

    return $http.get('billar/script_php/combo_id_servicio.php');
  };


  this.tabla_estado = function () {

    return $http.get('billar/script_php/seleccionar_actual.php');

  };






});


/*
 ###############################################
 INICIA EL CONTROLADOR
 ###############################################
 */


app.controller('controlador_billar', function ($scope, $log, $route, $http, cargar_registros, cargar_servicios,  socket) {
  

  cargar_servicios.session_usuario().success(function (data) {
    try {
    
    socket.zocalo.on('connect', function(){});
    
    socket.zocalo.emit('agregar', data.usuario);
    
    socket.zocalo.emit('CambiarSala', 'billar');
    
    socket.zocalo.on('sala', function (rooms, current_room) {
      console.debug('salta', rooms + ' acual: ' + current_room);
    });
        
    socket.zocalo.on('actualizar', function (username, data) {
     console.debug(username,data);
     $scope.recargar();
    });
    
    } catch(err) {
    console.error(err.message);
    }

   });


  

  $scope.recargar = function () {

    navigator.vibrate(500);

    console.debug("se recargo la tabla seleccionar_actual");

    cargar_registros.tabla_estado().success(function (data) {
        $scope.registros_estado = data;
      });
  };


  $scope.actualizar = function (data) {    
    notificaciones_chrome("Actualizando Registros", "img/icono.png", "se ha actualizado la lista");
    socket.zocalo.emit('EnviarMensaje', data);
  };



  cargar_registros.tabla_servicio()
          .success(function (data) {
            $scope.registros_servicio = data;
          });

  cargar_registros.tabla_ubicacion()
          .success(function (data) {
            $scope.registros_ubicacion = data;
          });

  cargar_registros.tabla_estado()
          .success(function (data) {
            $scope.registros_estado = data;
          });

  cargar_registros.mesas_disponibles()
          .success(function (data) {
            $scope.registros_mesas_disponibles = data;
          });


  $scope.guardar_pago = function (valor_id) {

    var valor_url = "billar/script_php/guardar_pago.php";
    var valor_metodo = "POST";
    var datos = $.param({'id': valor_id});

    cargar_registros.respuesta_registros(valor_url, valor_metodo, datos)

            .success(function (data) {

              $scope.actualizar(data);


            })

            .error(function (data, status, headers, config) {
              console.error(data);
            });

  };

  $scope.eliminar_consumo = function (valor_id) {

    var valor_url = "billar/script_php/eliminar_consumo.php";
    var valor_metodo = "POST";
    var datos = $.param({'id': valor_id});

    cargar_registros.respuesta_registros(valor_url, valor_metodo, datos)

            .success(function (data) {


              $scope.actualizar(data);



              $('#modal1').closeModal();

            })

            .error(function (data, status, headers, config) {
              console.error(data);
            });

  };

  $scope.valor_consumos = function (valor_id) {

    var valor_url = "billar/script_php/seleccionar_consumo.php";
    var valor_metodo = "POST";
    var datos = $.param({'id': valor_id});

    cargar_registros.respuesta_registros(valor_url, valor_metodo, datos)

            .success(function (data) {
              $scope.registros_consumos = data;
              $('#modal1').openModal();
            })

            .error(function (data, status, headers, config) {
              console.error(data);
            });

  };

  $scope.enviar_formulario = function (id_formulario, url_formulario, metodo_formulario) {

    var valor_url = url_formulario;
    var valor_metodo = metodo_formulario;
    var valor_datos = $('#' + id_formulario).serialize();

    cargar_registros.respuesta_registros(valor_url, valor_metodo, valor_datos)

            .success(function (data) {

              $('#' + id_formulario).trigger("reset");


              if (data.success)
              {

                new Messi(data.suceso, {
                  center: true,
                  width: '250px',
                  title: 'exitoso',
                  titleClass: 'success',
                  center: true,
                  autoclose: 2000,
                  closeButton: true
                });


                // multiple envio de datos
                $scope.actualizar(data);


              } else {

                new Messi("ocurrio una advertencia: " + data.suceso, {
                  center: true,
                  width: '250px',
                  title: 'ocurrio un problema',
                  titleClass: 'anim warning',
                  closeButton: true,
                  center: true,
                  autoclose: 3000
                 
                });


                //$route.reload();

              }

            })

            .error(function (data, status, headers, config) {
                  new Messi("ocurrio un error: " , {
                  center: true,
                  width: '250px',
                  title: 'ocurrio un error comunicarlo con al administrador',
                  titleClass: 'anim error',
                  center: true,
                  autoclose: 3000,
                  closeButton: true
                });
              console.error(data);
            });


  };


});

/*
 ###############################################
 TERMINA EL CONTROLADOR
 ###############################################
 */



