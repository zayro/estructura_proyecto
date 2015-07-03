/*
 ###############################################
 INICIA EL MODULO
 ###############################################
 */


"use strict";

console.time("inicia carga angularjs");
var app = angular.module('app', ['ngRoute', 'ngSanitize', 'angular.filter', 'ngLocale', 'ngTouch', 'ngTable', 'aplicativo_billar', 'aplicativo_parqueadero']);


angular.element(document).ready(function () {

  console.info('cargo angular');

});


console.timeEnd("inicia carga angularjs");



/*
###############################################
INICIA DIRECTIVA
###############################################
*/


app.directive("fragmentoEntrada", function() {
return {
restrict: 'E',
templateUrl: "modulos/logueo/entrada.html",
link: function (scope, elemento, atributos){
}
//controller: 'controlador'
};
});