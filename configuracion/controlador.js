/*
 ###############################################
 INICIA EL CONTROLADOR INGRESO
 ###############################################
 */
app.controller('ingreso', function ($scope, $timeout, $location, $log, cargar_servicios) {

  cargar_servicios.select_session().success(function (data) {
    
    $log.log(data.identificacion);
    if (data.identificacion == "" || data.identificacion == undefined) {
      
      $location.path('/login/');

    } else {
          console.log('ingreso correctamente');    

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

  }


});

/*
 ###############################################
 INICIA EL CONTROLADOR APLICACIONES
 ###############################################
 */

app.controller('AppCtrl', function ($scope, $timeout, $window, $location, $log, $http, cargar_servicios) {
  
   cargar_servicios.select_session().success(function (data) {
    
    $log.log(data.identificacion);
    if (data.identificacion == "" || data.identificacion == undefined) {
      
      $location.path('/login/');

    } else {
      $scope.select_session_usuario = data;
      console.log('ingreso correctamente');    

    }
    
  });
  cargar_servicios.select_menu().success(function (data) {
    $scope.menu_logueo = data.registros;
  });

  cargar_servicios.select_menu_principal().success(function (data) {
    $scope.select_menu_principal = data.registros;
  });


  $scope.ocultar_menu = function () {
    $('.button-collapse').sideNav('hide');
  };

  $scope.mostrar_menu = function () {
    $('.button-collapse').sideNav('show');
  };





});

/*
 ###############################################
 DEMO
 ###############################################
 */

app.controller('demo', function ($scope, $http, cargar_servicios) {

  $scope.prueba = "texto";

  $scope.mostrar_consulta =
          cargar_registros.traer_consultas()
          .success(function (data) {
            $scope.resultados = data.registros;
          });

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

  $scope.formularios = function () {
    var valor_url = url_formulario;
    var valor_metodo = metodo_formulario;
    var valor_datos = $('#' + id_formulario).serialize();

    cargar_registros.respuesta_registros(valor_url, valor_metodo, valor_datos)

            .success(function (data) {

        //$('#'+id_formulario).trigger("reset");



            })

            .error(function (data, status, headers, config) {
              console.error(data);
            });

  }


});