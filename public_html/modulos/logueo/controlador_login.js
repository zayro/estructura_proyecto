/*
 ###############################################
 CONTROLADOR LOGIN
 ###############################################
 */

app.controller('login', function ($scope, $timeout, $log, $location, cargar_servicios) {

  console.info("ingreso al controlador login");

  $scope.enviar_formulario_login = function () {
    var valor_url = "modulos/logueo/login.php";
    var valor_metodo = "POST";
    var valor_datos = $('#formulario_logueo').serialize();

    cargar_servicios.http_respuesta(valor_url, valor_metodo, valor_datos)

            .success(function (msg) {

              if (msg == 'exitoso') {

                $location.path('modulo/ingreso/');
                Materialize.toast("Ingreso Exitoso" + "<span class='btn-flat green-text' >" + msg + "</span>", 4000);

              } else {
                Materialize.toast("Verificar los datos" + "<a class='btn-flat red-text' href='#login'>X<a>", 4000);
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

                $location.path('/ingreso/');
                Materialize.toast("Ingreso Exitoso" + "<span class='btn-flat green-text' >" + msg.suceso + "</span>", 4000);
                $('#' + modal).closeModal();

              } else {
                Materialize.toast("Datos no coinciden Verificarlos" + "<a class='btn-flat red-text' href='#login'>X<a>", 4000);

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

  $scope.enviar_validar_usuario = function ($valor) {
    
    var valor_url = "modulos/logueo/validar_usuario.php";
    var valor_metodo = "POST";
    var valor_datos = $('#formulario_cambio_clave').serialize();

    cargar_servicios.http_respuesta(valor_url, valor_metodo, valor_datos)

            .success(function (msg) {

              if (!msg.success) {

   
                Materialize.toast("Ingreso Exitoso" + "<span class='btn-flat green-text' >" + msg.suceso + "</span>", 4000);
            

              } else {
                Materialize.toast("Datos no coinciden Verificarlos" + "<a class='btn-flat red-text' href='#login'>X<a>", 4000);

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
