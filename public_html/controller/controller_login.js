/*
 ###############################################
 CONTROLADOR LOGIN
 ###############################################
 */

//app.controller('login', function ($scope, cargar_servicios) { });

function login($scope, $location, cargar_servicios, socket) {

  console.info("ingreso al controlador login");


  cargar_servicios.select_combo_empresas().success(function (data) {
    $scope.combo_empresas = data;
  });

  $scope.enviar_formulario_login = function () {

    var valor_url = "controller/controller_login.php";
    var valor_metodo = "POST";
    var valor_datos = $('#formulario_logueo').serialize();

    cargar_servicios.http_respuesta(valor_url, valor_metodo, valor_datos)

            .success(function (data) {

              var msg = data[0];

              $scope.respuesta_login = data;

              // ########### guarda la session #########
              localStorage.setItem('session_sistema', JSON.stringify(msg));
              console.info(msg);

              if (msg.success == 'true')
              {

                console.log("ingreso al sistema");
                // remueve el menu inferio del fullpage
                $('#fp-nav').remove();
                // remueve el efecto overflow de fullpage
                $("html, body").removeAttr('style');
                $("html, body").css("overflow", "auto");
                
                     
                // redigie al ingreso  
                $location.path('/principal');
                
                Materialize.toast("Ingreso Exitoso" + "<span class='btn-flat green-text' >" + msg.usuario + "</span>", 2000);

                

              }
              if (msg.success == 'false')
              {
                Materialize.toast("VERIFICAR LOS DATOS" + "<a class='btn-flat red-text' > Error <a>", 5000);
              }
              if (msg.success == 'conectado')
              {

                Materialize.toast("YA AHY ALGUIEN CONECTADO" + '<a onclick="$(\'#usuario_conectado\').openModal()" class="btn-flat yellow-text" > Advertencia <a>', 8000);
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

  $scope.enviar_formulario_recordar_clave = function () {

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

}