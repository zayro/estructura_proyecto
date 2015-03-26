/*
 ###############################################
 INICIA EL CONTROLADOR APLICACIONES
 ###############################################
 */

app.controller('AppCtrl', function ($scope, $timeout, $window, $location, $log, $http, cargar_servicios) {
  
  $scope.$on("update_parent_controller", function (event, message) {
    $scope.message = message;
    $scope.verificar_session();
     console.log('ingreso update_parent_controller AppCtrl');
  });

  $scope.verificar_session = function () {

    cargar_servicios.select_session().success(function (data) {

      $log.log(data.identificacion);
      
      $scope.select_session_usuario = data;
      
      if ($scope.select_session_usuario.identificacion == "" || $scope.select_session_usuario.identificacion == undefined) {

        $location.path('/login/');

      } else {

        cargar_servicios.select_menu().success(function (data) {
          $scope.menu_logueo = data.registros;
        });

        cargar_servicios.select_menu_principal().success(function (data) {
          $scope.select_menu_principal = data.registros;
        });

      }

    });
  
  };
  
  $scope.verificar_session();

  $scope.ocultar_menu = function () {
    $('.button-collapse').sideNav('hide');
  };

  $scope.mostrar_menu = function () {
    $('.button-collapse').sideNav('show');
  };





});
/*
 ###############################################
 INICIA EL CONTROLADOR INGRESO
 ###############################################
 */
app.controller('ingreso', function ($scope, $timeout, $location, $log, cargar_servicios) {

  cargar_servicios.select_session().success(function (data) {    
   
    if (data.identificacion == "" || data.identificacion == undefined) {
      
      $location.path('/login/');

    } else {
          console.log('ingreso correctamente');  
          $scope.$emit('update_parent_controller', 'ingreso');

    }
    
  });
  
  
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

/*
 ###############################################
 DEMO
 ###############################################
 */

app.controller('demo', function ($scope, $http, cargar_servicios) {

  $scope.jquery = function () {

    $.ajax({
      type: "POST",
      url: "some.php",
      data: {name: "John", location: "Boston"}
    })
            .done(function (msg) {
              alert("Data Saved: " + msg);
            })
            .fail(function (jqXHR, textStatus) {
              alert("Request failed: " + textStatus);
            });
  }

});