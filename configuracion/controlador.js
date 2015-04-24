/*
 ###############################################
 INICIA EL CONTROLADOR APLICACIONES
 ###############################################
 */

app.controller('AppCtrl', function ($scope, $timeout, $window, $location, $log, $http, cargar_servicios) {


// funcion se ejecuta cada vez que ingreso algun modulo
  $scope.verificar_session = function () {

    var request = $.ajax({
      url: "librerias/session_usuario.php",
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

        cargar_servicios.select_menu_principal().success(function (data) {
          $scope.select_menu_principal = data.registros;
        });

      }
    });

    request.fail(function (jqXHR, textStatus) {
      alert("Request failed: " + textStatus);
      cargar_servicios.set_validar_session(jqXHR);
    });

    $scope.ocultar_menu = function () {
      $('.button-collapse').sideNav('hide');
    };

    $scope.mostrar_menu = function () {
      $('.button-collapse').sideNav('show');
    };

  };



  $scope.$on("update_parent_controller", function (event, message) {
    $scope.mensaje = message;
    $scope.verificar_session();
    console.log('ingreso update_parent_controller AppCtrl');
  });





});
/*
 ###############################################
 INICIA EL CONTROLADOR INGRESO
 ###############################################
 */
app.controller('ingreso', function ($scope) {

// al controlador principal le digo que revice  si existe una session vigente
  $scope.$emit('update_parent_controller', 'ingreso');



});


/*
 ###############################################
 INICIA EL CONTROLADOR LOGIN
 ###############################################
 */

app.controller('login', function ($scope, $timeout, $log, $location, cargar_servicios) {

  $scope.enviar_formulario = function () {
    var valor_url = "modulos/logueo/login.php";
    var valor_metodo = "POST";
    var valor_datos = $('#formulario_logueo').serialize();

    cargar_servicios.http_respuesta(valor_url, valor_metodo, valor_datos)

            .success(function (msg) {

              if (msg == 'exitoso') {

                $location.path('/ingreso/');
                toast("Ingreso Exitoso" + "<span class='btn-flat green-text' >" + msg + "</span>", 4000);

              } else {
                toast("Verificar los datos" + "<a class='btn-flat red-text' href='#login'>X<a>", 4000);
              }

              //$('#'+id_formulario).trigger("reset");

            })

            .error(function (data, status, headers, config) {
              console.error(data);
            });

  };


});

