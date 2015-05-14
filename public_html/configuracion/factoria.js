/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */





app.factory('cargar_factorias', function($http){
        // interface
        var service = {
            resultado: "",
            getAlbums: getAlbums
        };
        return service;

        // implementation
        function getAlbums() {
         

            return $http.get("librerias/session_usuario.php")
                .success(function(data) {
                  console.log("resuelto"+data);
                    service.resultado = data;
                  
                })
                .error(function() {
                  
                });
            
        }
    });