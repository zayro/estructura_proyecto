/*
 ###############################################
 INICIA EL MODULO
 ###############################################
 */


var app = angular.module('aplicativo_billar', []);


/*
 ###############################################
 INICIA FILTROS
 ###############################################
 */


app.filter('filtro_sumatoria', function () {

  return function (data, key) {

    if (angular.isUndefined(data)) {
      return 0;
    } else {


      if (typeof (data) === 'undefined' && typeof (key) === 'undefined') {

        return 0;

      } else {


        var sum = 0;

        for (var i = 0; i < data.length; i++) {

          sum = (sum + parseInt(data[i][key]));

        }

//debugger;

        if (sum == 'undefined') {
          return 0;
        } else {
          return sum;
        }

      }
    }
  };

});


app.filter('filtro_multiplicar', function () {


  return function (data, key1, key2) {


    if (angular.isUndefined(data)) {
      return 0;
    } else {



      if (typeof (data) === 'undefined' && typeof (key1) === 'undefined' && typeof (key2) === 'undefined') {
        return 0;
      } else {

        var sum = 0;

        for (var i = 0; i < data.length; i++) {

          sum = (sum + parseInt((data[i][key1] * data[i][key2])));

        }

        if (sum == 'undefined') {
          return 0;
        } else {
          return sum;
        }
      }

    }
  };

});

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


app.controller('controlador_billar', function ($scope, $http, cargar_registros) {

angular.element(document).ready(function () {

  console.log('cargo aplicativo billar');
});

  var sync_datos_billar = new Firebase('https://billar.firebaseio.com/connected');

  sync_datos_billar.on('value', function (snap) {
    if (snap.val() === true) {
      console.log("desconectado a fire base ");


      var con = sync_datos_billar.push(true);
      // when I disconnect, remove this device
      con.onDisconnect().remove();

    } else
    {
      console.log("conectado a fire base ");

      // var sync_datos_billar = new Firebase('https://billar.firebaseio.com/');



    }
  });

  sync_datos_billar.on('child_changed', function (snapshot) {
    $scope.recargar();
  });


  $scope.recargar = function () {
    console.log("se recargo la tabla seleccionar_actual");
    cargar_registros.tabla_estado()
            .success(function (data) {
              $scope.registros_estado = data;
            });
  }

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

              sync_datos_billar.update({actualizar: {evento: data}}, $scope.recargar());

              cargar_registros.tabla_estado()
                      .success(function (data) {
                        $scope.registros_estado = data;
                      });

              cargar_registros.mesas_disponibles()
                      .success(function (data) {
                        $scope.registros_mesas_disponibles = data;
                      });
            })

            .error(function (data, status, headers, config) {
              console.error(data);
            });

  }

  /*
   $scope.buscar_informe_pagos = function (id_formulario) {
   
   var valor_url = "script_php/seleccionar_informe_pagos.php";
   var valor_metodo = "POST";
   var datos = $('#' + id_formulario).serialize();
   
   cargar_registros.respuesta_registros(valor_url, valor_metodo, datos)
   .success(function (data) {
   
   $scope.informe_pagos = data;
   
   
   })
   
   .error(function (data, status, headers, config) {
   console.error(data);
   });
   
   }
   
   $scope.buscar_informe_tiempo = function (id_formulario) {
   
   var valor_url = "script_php/seleccionar_informe_tiempo.php";
   var valor_metodo = "POST";
   var datos = $('#' + id_formulario).serialize();
   
   cargar_registros.respuesta_registros(valor_url, valor_metodo, datos)
   .success(function (data) {
   
   $scope.informe_tiempo = data;
   
   
   })
   
   .error(function (data, status, headers, config) {
   console.error(data);
   });
   
   }
   
   */

  $scope.eliminar_consumo = function (valor_id) {

    var valor_url = "billar/script_php/eliminar_consumo.php";
    var valor_metodo = "POST";
    var datos = $.param({'id': valor_id});

    cargar_registros.respuesta_registros(valor_url, valor_metodo, datos)

            .success(function (data) {

              sync_datos_billar.update({actualizar: {evento: data}}, $scope.recargar());

              cargar_registros.tabla_estado()
                      .success(function (data) {
                        $scope.registros_estado = data;
                      });

              cargar_registros.mesas_disponibles()
                      .success(function (data) {
                        $scope.mesas_disponibles = data;
                      });


              $('#modal1').closeModal();

            })

            .error(function (data, status, headers, config) {
              console.error(data);
            });

  }

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

  }

  $scope.enviar_formulario = function (id_formulario, url_formulario, metodo_formulario) {

    var valor_url = url_formulario;
    var valor_metodo = metodo_formulario;
    var valor_datos = $('#' + id_formulario).serialize();

    cargar_registros.respuesta_registros(valor_url, valor_metodo, valor_datos)

            .success(function (data) {

              sync_datos_billar.update({actualizar: {evento: data}}, $scope.recargar());

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


              } else {

                new Messi(data.suceso, {
                  center: true,
                  width: '250px',
                  title: 'ocurrio un problema',
                  titleClass: 'anim error',
                  center: true,
                          autoclose: 2000,
                  closeButton: true
                });

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



