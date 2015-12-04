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
              templateUrl: 'proyect/billar/modulos/panel_consumos.html',
              controller: 'valida_usuario'
            }).
            when('/billar/modulos/panel_ubicacion/', {
              templateUrl: 'proyect/billar/modulos/panel_ubicacion.html',
              controller: 'valida_usuario'
            }).
            when('/billar/modulos/panel_pago/', {
              templateUrl: 'proyect/billar/modulos/panel_pago.html',
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

    return $http.get('proyect/billar/script_php/seleccionar_disponible.php');

  };


  this.tabla_ubicacion = function () {

    return $http.get('proyect/billar/script_php/combo_id_ubicacion.php');

  };


  this.tabla_servicio = function () {

    return $http.get('proyect/billar/script_php/combo_id_servicio.php');
  };


  this.tabla_estado = function () {

    return $http.get('proyect/billar/script_php/seleccionar_actual.php');

  };



});


/*
 ###############################################
 INICIA EL CONTROLADOR
 ###############################################
 */


app.controller('controlador_billar', function ($scope, $log, $route, $http, cargar_registros, cargar_servicios, socket) {


  cargar_servicios.session_usuario().success(function (data) {
    try {

      socket.zocalo.on('connect', function () {
      });

      socket.zocalo.emit('agregar', data.usuario);

      socket.zocalo.emit('CambiarSala', 'billar');

      socket.zocalo.on('sala', function (rooms, current_room) {
        console.debug('salta', rooms + ' acual: ' + current_room);
      });

      socket.zocalo.on('actualizar', function (username, data) {
        console.debug(username, data);
        if (data.actualizar_billar) {
          notificaciones_chrome("Actualizando Registros", "assets/images/logos/icono.png", "se ha actualizado la lista");
          navigator.vibrate(500);
          $scope.recargar();
        }
      });

    } catch (err) {
      console.error(err.message);
    }

  });


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
                // $scope.actualizar(data);


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
              new Messi("ocurrio un error: ", {
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

  $scope.recargar = function () {
    cargar_registros.tabla_estado()
            .success(function (data) {
              $scope.registros_estado = data;
            });
  };



  $scope.recargar();

  $scope.guardar_pago = function (valor_id) {

    var valor_url = "proyect/billar/script_php/guardar_pago.php";
    var valor_metodo = "POST";
    var datos = $.param({'id': valor_id});

    cargar_registros.respuesta_registros(valor_url, valor_metodo, datos)

            .success(function (data) {

              //   $scope.actualizar(data);
              data['actualizar_consumo'] = true;
              socket.zocalo.emit('EnviarMensaje', data);
              $scope.recargar();

            })

            .error(function (data, status, headers, config) {
              console.error(data);
            });

  };

  $scope.eliminar_consumo = function (valor_id) {

    var valor_url = "proyect/billar/script_php/eliminar_consumo.php";
    var valor_metodo = "POST";
    var datos = $.param({'id': valor_id});

    cargar_registros.respuesta_registros(valor_url, valor_metodo, datos)

            .success(function (data) {

              //$scope.actualizar(data);

              $scope.recargar();

              $('#modal1').closeModal();

            })

            .error(function (data, status, headers, config) {
              console.error(data);
            });

  };

  $scope.valor_consumos = function (valor_id) {

    var valor_url = "proyect/billar/script_php/seleccionar_consumo.php";
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



});

/*
 ###############################################
 INICIA EL CONTROLADOR CONSUMOS
 ###############################################
 */

app.controller('controlador_consumo', function ($scope, $log, $route, $http, cargar_registros, cargar_servicios, socket) {

  cargar_servicios.session_usuario().success(function (data) {
    try {

      socket.zocalo.on('connect', function () {
      });

      socket.zocalo.emit('agregar', data.usuario);

      socket.zocalo.emit('CambiarSala', 'billar');

      socket.zocalo.on('sala', function (rooms, current_room) {
        console.debug('salta', rooms + ' acual: ' + current_room);
      });

      socket.zocalo.on('actualizar', function (username, data) {
        console.debug(username, data);
        if (data.actualizar_consumo) {
          $scope.recargar();
        }
      });

    } catch (err) {
      console.error(err.message);
    }

  });



  $scope.recargar = function () {

    cargar_registros.mesas_disponibles()
            .success(function (data) {
              $scope.registros_mesas_disponibles = data;
            });

    cargar_registros.tabla_servicio()
            .success(function (data) {
              $scope.registros_servicio = data;
            });


  };

  $scope.recargar();



  $scope.guardar_consumos = function () {

    var valor_url = "proyect/billar/script_php/guardar_consumo.php";
    var valor_metodo = "POST";
    var valor_datos = $('#enviar_consumo').serialize();

    cargar_registros.respuesta_registros(valor_url, valor_metodo, valor_datos)

            .success(function (data) {

              $('#enviar_consumo').trigger("reset");


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
                //$scope.actualizar(data);
                data['actualizar_billar'] = true;
                socket.zocalo.emit('EnviarMensaje', data);


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
              console.error(data);
            });

  };



});

/*
 ###############################################
 INICIA EL CONTROLADOR UBICACION
 ###############################################
 */

app.controller('controlador_ubicacion', function ($scope, $log, $route, $http, cargar_registros, cargar_servicios, socket) {

  cargar_servicios.session_usuario().success(function (data) {
    try {

      socket.zocalo.on('connect', function () {
      });

      socket.zocalo.emit('agregar', data.usuario);

      socket.zocalo.emit('CambiarSala', 'billar');

      socket.zocalo.on('sala', function (rooms, current_room) {
        console.debug('salta', rooms + ' acual: ' + current_room);
      });



    } catch (err) {
      console.error(err.message);
    }

  });

  $scope.recargar = function () {
    cargar_registros.tabla_ubicacion().success(function (data) {
      $scope.registros_ubicacion = data;
    });
  };


  $scope.recargar();

  $scope.guardar_ubicacion = function () {

    var valor_url = "proyect/billar/script_php/guardar_tiempo.php";
    var valor_metodo = "POST";
    var valor_datos = $('#enviar_ubicacion').serialize();

    cargar_registros.respuesta_registros(valor_url, valor_metodo, valor_datos)

            .success(function (data) {

              $('#enviar_consumo').trigger("reset");


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
                //$scope.actualizar(data);

                data['actualizar_billar'] = true;
                socket.zocalo.emit('EnviarMensaje', data);



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
              console.error(data);
            });

  };




});

/*
 ###############################################
 TERMINA EL CONTROLADOR
 ###############################################
 */



