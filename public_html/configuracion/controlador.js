/*
 ###############################################
 INICIA EL CONTROLADOR APLICACIONES
 ###############################################
 */

app.controller('AppCtrl', function ($scope, $timeout, $window, $location, $log, $http, cargar_servicios) {

  /*
   $scope.$on('$locationChangeStart', function( event ) {
   var answer = confirm("Are you sure you want to leave this page?")
   if (!answer) {
   event.preventDefault();
   }
   });
   */

  // funcion se ejecuta cada vez que ingreso algun modulo
  $scope.verificar_session = function () {



    var request = $.ajax({
      url: "../librerias/session_usuario.php",
      method: "get",
      dataType: "json",
      beforeSend: function () {
        console.log('se enviaran los datos para verificar la sesion');
      }
    });

    request.done(function (data) {

      console.info("DATOS DE SESSION: %O", data);

      cargar_servicios.set_validar_session(data);


      $scope.select_session_usuario = cargar_servicios.validar_session();

      var identificacion = $scope.select_session_usuario.identificacion;


      if (identificacion == "" || typeof identificacion === 'undefined' || !identificacion) {


        window.location = "#/login/";

      } else {

        cargar_servicios.select_menu().success(function (data) {
          $scope.menu_logueo = data.registros;
          console.info("MENU : %O ", data.registros);
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
      $('#modal_menu').closeModal();
    };

    $scope.mostrar_menu = function () {
      $('.button-collapse').sideNav('show');
    };

  };

// se recibe la comprobacion del login
  $scope.$on("update_parent_controller", function (event, message) {
    $scope.mensaje = message;
    $scope.verificar_session();
    console.log('trigger update_parent_controller AppCtrl');
  });


});

/*
 ##################################################
 INICIA EL CONTROLADOR QUE VALIDA SI EXISTE SESSION
 ###################################################
 */

app.controller('valida_usuario', function ($scope, cargar_servicios) {

  console.group("ingreso al controlador valida usuario");

  /**
   *  HABILITA LOS EVENTOS DEL TECLADO
   * @param {type} evento
   * @returns {undefined}
   */
  function operaEvento(evento) {
    console.log("type", evento.type);
    console.log("which", evento.which);
    $(document).keypress(operaEvento);
  }



  /**
   * MENSAJES DE RESPUESTA
   */
  function isOnline() {
    console.info("En Linea: INTERNET");
    Materialize.toast("Se conecto " + "<span class='btn-flat green-text' >Internet</span>", 40000);
  }

  function isOffline() {
    console.error("Fuera de Linea: SIN INTERNET");
    Materialize.toast("se perdio conexion  " + "<a class='btn-flat red-text' href='#login'>Internet<a>", 40000);
    $('#cargando').openModal();
  }


  /**
   * VERIFICAMOS SI EL NAVEGADOR ESTA CONECTADO A INTERNET AL ABRIRLO
   */
  if (navigator.onLine)
  {
    console.info('ONLINE!');
  } else {
    console.error('OFFLINE');
  }


  /**
   * VERIFICAMOS SI EL NAVEGADOR PIERDE CONEXION A INTERNET
   */
  if (window.addEventListener) {
    /*
     Works well in Firefox and Opera with the 
     Work Offline option in the File menu.
     Pulling the ethernet cable doesn't seem to trigger it.
     Later Google Chrome and Safari seem to trigger it well
     */
    window.addEventListener("online", isOnline, false);
    window.addEventListener("offline", isOffline, false);
    console.warn("nuevos navegadores");
  }
  else {
    /*
     Works in IE with the Work Offline option in the 
     File menu and pulling the ethernet cable
     */
    document.body.ononline = isOnline;
    document.body.onoffline = isOffline;
    console.warn("antiguo navegadores");
  }

  // al controlador principal le digo que revice  si existe una session vigente
  $scope.$emit('update_parent_controller', 'ingreso');

  console.groupEnd();
});

/*
 ###############################################
 CONTROLADOR INGRESO
 ###############################################
 */

app.controller('ingreso', function ($scope, cargar_servicios) {



  $scope.enviar_formulario = function (datos, valor_url) {
    console.group("ingreso al controlador ingreso");

    var valor_metodo = "POST";
    var valor_datos = $('#' + datos).serialize();

    cargar_servicios.http_respuesta(valor_url, valor_metodo, valor_datos)

            .success(function (msg) {

              if (msg.success) {

                Materialize.toast("Exitoso" + "<span class='btn-flat green-text' >" + msg.suceso + "</span>", 4000);

                //setTimeout("window.print()" , 5000);


                cargar_servicios.http_respuesta('modulos/ingreso/consultar_pago.php', 'post', $.param({'placa': msg.placa})).success(function (data) {



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
    console.groupEnd();

  };



});

/*
 ###############################################
 CONTROLADOR LOGIN
 ###############################################
 */

app.controller('login', function ($scope, cargar_servicios) {

  console.info("ingreso al controlador login");

  $scope.enviar_formulario_login = function () {
    var valor_url = "modulos/logueo/login.php";
    var valor_metodo = "POST";
    var valor_datos = $('#formulario_logueo').serialize();

    cargar_servicios.http_respuesta(valor_url, valor_metodo, valor_datos)

            .success(function (msg) {

              if (msg == 'exitoso') {
                console.clear();
                console.log("ingreso al sistema");


                window.location = '#modulos/ingreso';
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

                $location.path('#modulos/ingreso');
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