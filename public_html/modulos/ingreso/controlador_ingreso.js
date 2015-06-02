/*
 ###############################################
 CONTROLADOR INGRESO
 ###############################################
 */
app.controller('ingreso', function ($scope, cargar_servicios) {

console.info("ingreso al controlador ingreso");




  $scope.enviar_formulario = function (datos, valor_url) {


    var valor_metodo = "POST";
    var valor_datos = $('#' + datos).serialize();

    cargar_servicios.http_respuesta(valor_url, valor_metodo, valor_datos)

            .success(function (msg) {

              if (msg.success) {

                Materialize.toast("Exitoso" + "<span class='btn-flat green-text' >" + msg.suceso + "</span>", 4000);

                //setTimeout("window.print()" , 5000);


                cargar_servicios.http_respuesta('modulos/ingreso/consultar_pago.php', 'post', $.param({'placa': msg.placa})).success(function (data) {

                  $scope.placa = data.registros[0].placa;
                  $scope.tiempo_entrada = data.registros[0].tiempo_entrada;
                  $scope.factura = data.registros[0].factura;
                  $scope.tiempo_salida = data.registros[0].tiempo_salida;
                  $scope.valor = Number(data.registros[0].valor);

                });


              } else {
                Materialize.toast("Error" + "<a class='btn-flat red-text' href='#login'>X<a>", 5000);
                Materialize.toast(msg.suceso + "<a class='btn-flat red-text' href='#login'>X<a>", 5000);
              }

              //$('#'+id_formulario).trigger("reset");

            })

            .error(function (data, status, headers, config) {
              console.error(data);
            });

  };


});



