/*
 ###############################################
 INICIA EL CONTROLADOR APLICACIONES
 ###############################################
 */

app.controller('AppCtrl', function ($scope, $timeout, $window, $location, $log, $http, cargar_servicios) {


// funcion se ejecuta cada vez que ingreso algun modulo
  $scope.verificar_session = function () {

    var request = $.ajax({
      url: "../librerias/session_usuario.php",
      method: "get",
      dataType: "json"
    });

    request.done(function (data) {
      cargar_servicios.set_validar_session(data);

      $scope.select_session_usuario = cargar_servicios.validar_session();

      var identificacion = $scope.select_session_usuario.identificacion;


      if (identificacion == "" || typeof identificacion === 'undefined' || !identificacion) {


        window.location = "#/login/";

      } else {

        cargar_servicios.select_menu().success(function (data) {
          $scope.menu_logueo = data.registros;
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
    };

    $scope.mostrar_menu = function () {
      $('.button-collapse').sideNav('show');
    };

  };


// se recibe la comprobacion del login
  $scope.$on("update_parent_controller", function (event, message) {
    $scope.mensaje = message;
    $scope.verificar_session();
    console.log('ingreso update_parent_controller AppCtrl');
  });





});
/*
 ###############################################
 CONTROLADOR INGRESO
 ###############################################
 */
app.controller('ingreso', function ($scope, cargar_servicios) {

// al controlador principal le digo que revice  si existe una session vigente
  $scope.$emit('update_parent_controller', 'ingreso');


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


/*
 ###############################################
 CONTROLADOR LOGIN
 ###############################################
 */

app.controller('login', function ($scope, $timeout, $log, $location, cargar_servicios) {

  $scope.enviar_formulario_login = function () {
    var valor_url = "modulos/logueo/login.php";
    var valor_metodo = "POST";
    var valor_datos = $('#formulario_logueo').serialize();

    cargar_servicios.http_respuesta(valor_url, valor_metodo, valor_datos)

            .success(function (msg) {

              if (msg == 'exitoso') {

                $location.path('/ingreso/');
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
                $('#'+modal).closeModal();

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


});

