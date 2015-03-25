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


  this.select_session = function () {
    return $http.get('librerias/session_usuario.php');
  };


  this.select_menu = function () {
    return $http.get('modulos/menu/select_menu.php');
  };

  this.select_menu_principal = function () {
    return $http.get('modulos/menu/select_menu_principal.php');
  };

  this.set_activar_menu = function (valor) {
    this.acceso = valor;
  }

  this.get_activar_menu = function () {
    return this.acceso;
  }


});