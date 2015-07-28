
console.time("inicia carga jquery");

/**
 * ######################################
 * CARGAR DOM JQUERY
 * ######################################
 */
$(document).ready(function () {


  /**
   * ######################################
   *  HABILITA LOS EVENTOS DEL TECLADO
   * ######################################
   * @param {type} evento
   * @returns {undefined}
   */
  
  function procesarEvento(evento) {

  console.log("type:", evento.type,"which:", evento.which);
  console.log("----------------------------------------")

    if (evento.type == "keypress" && evento.which == "9829") {
      toggleFullScreen();
    }

/*
 * se tienen que sacar las 3 lineas de abajo para que funcione la funcion 
 */
 
  $(document).keydown(procesarEvento);
  $(document).keypress(procesarEvento);
  $(document).keyup(procesarEvento);

  }


  /**
   * ######################################
   * VERIFICAMOS SI EL NAVEGADOR ESTA CONECTADO A INTERNET AL ABRIRLO
   * ######################################
   */
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


  /**
   * ######################################
   * CARGAR PLUGIN FULL SCREEN
   * ######################################
   */

  console.log("full screen");
  function toggleFullScreen() {
    if (!document.fullscreenElement && // alternative standard method
            !document.mozFullScreenElement && !document.webkitFullscreenElement && !document.msFullscreenElement) {  // current working methods
      if (document.documentElement.requestFullscreen) {
        document.documentElement.requestFullscreen();
      } else if (document.documentElement.msRequestFullscreen) {
        document.documentElement.msRequestFullscreen();
      } else if (document.documentElement.mozRequestFullScreen) {
        document.documentElement.mozRequestFullScreen();
      } else if (document.documentElement.webkitRequestFullscreen) {
        document.documentElement.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
      }
    } else {
      if (document.exitFullscreen) {
        document.exitFullscreen();
      } else if (document.msExitFullscreen) {
        document.msExitFullscreen();
      } else if (document.mozCancelFullScreen) {
        document.mozCancelFullScreen();
      } else if (document.webkitExitFullscreen) {
        document.webkitExitFullscreen();
      }
    }
  }


  /**
   * ######################################
   * CARGAR MANEJADOR DE PROTOCOLO
   * ######################################
   */

  console.info("activando manejador");
  navigator.registerProtocolHandler("web+zav", "http://localhost/estructura_proyecto/?uri=%s", "manejador zav");

  /*
   * ########################################
   * ANCHO DEL SIDEBAR TEMA MATERIALIZE
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



