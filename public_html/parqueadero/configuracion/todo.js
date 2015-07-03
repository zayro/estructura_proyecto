/*
 ###############################################
 INICIA EL MODULO
 ###############################################
 */


"use strict";

var app = angular.module('aplicativo_parqueadero', []);


angular.element(document).ready(function () {

  console.log('cargo angular');
});
/*
 ###############################################
 INICIA RUTAS
 ###############################################
 */

app.config(['$routeProvider',
  function ($routeProvider) {
    $routeProvider.
            when('/parqueadero/modulo/panel_ingreso/', {
              templateUrl: 'parqueadero/modulos/ingreso/ingreso.html',
              controller: 'valida_usuario'
            }).
            when('/parqueadero/modulo/panel_salida', {
              templateUrl: 'parqueadero/modulos/ingreso/salida.html',
              controller: 'valida_usuario'
            }).
            when('/parqueadero/modulo/panel_parqueadero', {
              templateUrl: 'parqueadero/modulos/ingreso/parqueadero.html',
              controller: 'valida_usuario'
            }).
            when('/parqueadero/informe_movimientos/', {
              templateUrl: 'parqueadero/modulos/informes/informe_movimientos.html',
              controller: 'valida_usuario'
            }).
            when('/parqueadero/informe_pendientes/', {
              templateUrl: 'parqueadero/modulos/informes/informe_pendientes.html',
              controller: 'valida_usuario'
            });
  }]);


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


});

/*
 ###############################################
 INICIA EL CONTROLADOR INGRESO
 ###############################################
 */


app.controller('controlador_ingreso_parqueadero', function ($scope, cargar_servicios) {

  $scope.enviar_formulario_entrada = function (datos) {


    var valor_metodo = "POST";
    var valor_datos = $('#' + datos).serialize();
    var valor_url = 'parqueadero/modulos/ingreso/insertar_vehiculo.php';

    cargar_servicios.http_respuesta(valor_url, valor_metodo, valor_datos)

            .success(function (msg) {

              if (msg.success) {

                Materialize.toast("Exitoso" + "<span class='btn-flat green-text' >" + msg.suceso + "</span>", 2000);

              } else {
                Materialize.toast("Ocurrio con error mirar la consola" + "<a class='btn-flat red-text' href='#login'>X<a>", 5000);
                // Materialize.toast(msg.suceso + "<a class='btn-flat red-text' href='#login'>X<a>", 5000);
                console.error(msg.suceso);
              }

              //$('#'+id_formulario).trigger("reset");

            })

            .error(function (data, status, headers, config) {
              console.error(data);
            });

  };

  $scope.enviar_formulario_salida = function (datos) {


    var valor_metodo = "POST";
    var valor_datos = $('#' + datos).serialize();
    var valor_url = 'parqueadero/modulos/ingreso/actualizar_vehiculo.php';

    cargar_servicios.http_respuesta(valor_url, valor_metodo, valor_datos)

            .success(function (msg) {

              if (msg.success) {






                cargar_servicios.http_respuesta('parqueadero/modulos/ingreso/consultar_pago.php', 'post', $.param({'placa': msg.placa})).success(function (data) {

                  console.log(data[0]);

                  if (data[0]) {
                    Materialize.toast("Exitoso" + "<span class='btn-flat green-text' >" + msg.suceso + "</span>", 500);
                    $scope.placa = data[0].placa;
                    $scope.tiempo_entrada = data[0].tiempo_entrada;
                    $scope.factura = data[0].factura;
                    $scope.tiempo_salida = data[0].tiempo_salida;
                    $scope.valor = Number(data[0].valor);

                    setTimeout("window.print()", 1000);
                  } else {

                    Materialize.toast("No hay un vehiculo con esta placa" + "<a class='btn-flat red-text' href='#login'>X<a>", 5000);
                  }

                });



              } else {
                Materialize.toast("Ocurrio con error mirar la consola" + "<a class='btn-flat red-text' href='#login'>X<a>", 5000);
                // Materialize.toast(msg.suceso + "<a class='btn-flat red-text' href='#login'>X<a>", 5000);
                console.error(msg.suceso);
              }

              //$('#'+id_formulario).trigger("reset");

            })

            .error(function (data, status, headers, config) {
              console.error(data);
            });

  };


});


