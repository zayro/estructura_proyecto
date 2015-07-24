
console.time("inicia carga jquery");

/**
 * ######################################
 * CARGAR DOM JQUERY
 * ######################################
 */
$(document).ready(function () {

  /*
   * ########################################
   * ANCHO DEL SIDEBAR
   * ########################################
   */

  $('.button-collapse').sideNav({
    menuWidth: 250, // Default is 240
    edge: 'left', // Choose the horizontal origin
    closeOnClick: true // Closes side-nav on <a> clicks, useful for Angular/Meteor
  }
  );

  function lateral() {

    $('.button-collapse').sideNav({
      menuWidth: 340, // Default is 240
      edge: 'right' // Choose the horizontal origin
              //  closeOnClick: true // Closes side-nav on <a> clicks, useful for Angular/Meteor
    });

    $('.collapsible').collapsible({
      accordion: false // A setting that changes the collapsible behavior to expandable instead of the default accordion style
    });

    $('select').material_select();
  }
  /*
   * ########################################
   * PARA METRIAZACION DEL TIEMPO DE ESPERA
   * ########################################
   */


  $.idle(600, function () {
    console.info('Llevas 10 minutos inactivo');
  });

  $.idle(900, function () {
    $.away('cliente ausente');
  });

  $.idle(0, function () {
    if ($.isAway()) {
      console.log("BIENVENIDO DE VUELTA ");
      $.away();
    }
  });


  console.info("cargo jquery");
});




$(window).load(function () {
  console.log("se terminod de cargar la pagina");
  //$('#carga_inicial').closeModal();
});

console.timeEnd("inicia carga jquery");

/*
 * ########################################
 * AL TERMINAR DE CARGAR LOS SCRIPTS
 * ########################################
 */

console.time("carga total pagina");


console.timeEnd("carga total pagina");




/*
 ###############################################
 INICIA EL CONTROLADOR APLICACIONES
 ###############################################
 */

app.controller('AppCtrl', function ($scope, $route, $routeParams, $location, $log, $http, cargar_servicios) {

  /*
   ###############################################
   CONFIGURACION INICIO SESSION GOOGLE
   ###############################################
   */

  $scope.$route = $route;
  $scope.$location = $location;
  $scope.$routeParams = $routeParams;




// Register the callback to be fired every time auth state changes
  var ref = new Firebase("https://estructuraproyecto.firebaseio.com");
  /*
   ###############################################
   CONFIGURACION DEL TEMA
   ###############################################
   */

  angular.element(document).ready(function () {
    //$('#carga_inicial').openModal();
    console.info("cargo controlador principal");
  });

  if (localStorage.getItem("tema") === null) {
    var tema = {'color_menu': 'blue-grey darken-4', 'color_sidebar': 'grey darken-3'};
    localStorage.setItem('tema', JSON.stringify(tema));

  }
  var storage_tema = localStorage.getItem("tema");
  var datos_tema = JSON.parse(storage_tema);
  $scope.color_menu = datos_tema.color_menu;
  $scope.color_sidebar = datos_tema.color_sidebar;



  $scope.$on('$locationChangeStart', function (event) {
    console.warn("se recargo el navegador");
    console.clear();
    /*var answer = confirm("Desea salir del sistema?");
     if (!answer) {
     event.preventDefault();
     }
     */
  });


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

      $scope.select_session_usuario = data;

      var identificacion = $scope.select_session_usuario.identificacion;
      var storage = localStorage.getItem("session_sistema");
      var datos_session = JSON.parse(storage);

      var valida_modulo = $.ajax({
        url: "modulos/logueo/select_permisos.php",
        method: "post",
        data: {modulo_actual: '#' + $location.path()},
        dataType: "json",
        beforeSend: function () {
          console.log('se enviaran los datos para verificar modulo');
        }
      });
      
      console.log('#' + $location.path());

      valida_modulo.done(function (data) {
        if (data.registros_encontrado == 0) {
          // verifica los permisos de ingreso al modulo
          window.location = "#/login/";
        }
      });

      valida_modulo.fail(function (jqXHR, textStatus) {
        console.error("Error: ");
        console.error(textStatus);
        console.error(jqXHR);       
      });

      if (
              identificacion == "" ||
              typeof identificacion === 'undefined' ||              
              datos_session.empresa != $scope.select_session_usuario.empresa

              ) {

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

  console.groupCollapsed("ingreso al controlador valida usuario");

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


  function isOnline() {
    console.info("En Linea: INTERNET");
    Materialize.toast("Se conecto " + "<span class='btn-flat green-text' >Internet</span>", 40000);
    $('#cargando').closeModal();
  }

  function isOffline() {
    console.error("Fuera de Linea: SIN INTERNET");
    Materialize.toast("se perdio conexion  " + "<a class='btn-flat red-text'  >Internet<a>", 40000);
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
  $scope.$emit('update_parent_controller', 'valida_ingreso');

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

              } else {
                Materialize.toast("Error" + "<a class='btn-flat red-text'  >X<a>", 5000);
                Materialize.toast(msg.suceso + "<a class='btn-flat red-text'  >X<a>", 5000);
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

  cargar_servicios.select_combo_empresas().success(function (data) {
    $scope.combo_empresas = data;
  });
  $scope.enviar_formulario_login = function () {
    var valor_url = "modulos/logueo/login.php";
    var valor_metodo = "POST";
    var valor_datos = $('#formulario_logueo').serialize();

    cargar_servicios.http_respuesta(valor_url, valor_metodo, valor_datos)

            .success(function (msg) {


              localStorage.setItem('session_sistema', JSON.stringify(msg));

              if (msg.success) {
                //console.clear();
                console.log("ingreso al sistema");
                // remueve el menu inferio del fullpage
                $('#fp-nav').remove();
                // remueve el efecto overflow de fullpage
                $("html, body").removeAttr('style');
                $("html, body").css("overflow", "auto");
                // redigie al ingreso
                window.location = '#modulos/ingreso';
                Materialize.toast("Ingreso Exitoso" + "<span class='btn-flat green-text' >" + msg.usuario + "</span>", 4000);

              } else {
                Materialize.toast("VERIFICAR LOS DATOS" + "<a class='btn-flat red-text' > Error <a>", 4000);
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


                Materialize.toast("SE CAMBIO CLAVE" + "<span class='btn-flat green-text' >" + msg.suceso + "</span>", 4000);
                $('#' + modal).closeModal();

              } else {
                Materialize.toast("VERIFICAR LOS DATOS" + "<a class='btn-flat red-text'  > Error<a>", 4000);

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

  $scope.enviar_formulario_recordar_clave = function ($valor) {

    var valor_url = "modulos/logueo/validar_usuario.php";
    var valor_metodo = "POST";
    var valor_datos = $('#formulario_recordar_clave').serialize();

    cargar_servicios.http_respuesta(valor_url, valor_metodo, valor_datos)

            .success(function (msg) {

              if (!msg.success) {


                Materialize.toast("ENVIANDO MENSAJE ..." + "<span class='btn-flat green-text' >" + msg.suceso + "</span>", 4000);


              } else {
                Materialize.toast("VERIFICAR LOS DATOS" + "<a class='btn-flat red-text'  > Error<a>", 4000);

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