(function () {


  $.fn.extend({
    /**
     * 
     * @param {type} options
     * @returns {extenciones_query_L8.extenciones_queryAnonym$0}
     */
    stripeMe: function (options) {
      var defaults = {
        className: 'dark',
        pseudoSort: ':even'
      };
      options = $.extend(defaults, options);
      this.each(function () {
        $(this).children(options.pseudoSort).addClass(options.className);
      });
      return this;
    },
    /**
     * 
     * @param {type} options
     * @returns {extenciones_query_L8.extenciones_queryAnonym$0}
     */
    nudgeMe: function (options) {
      var defaults = {
        color: 'yellow'
      };
      options = $.extend(defaults, options);
      this.each(function () {
        $(this).hover(function () {
          $(this).css('background', options.color);
        }, function () {
          $(this).css('background', 'transparent');
        });
      });
      return this;
    },
    /**
     * 
     * @returns {extenciones_query_L8.extenciones_queryAnonym$0@call;each}
     */
    enrojo: function () {

      return this.each(function () {

        $(this).css({'background-color': '#ff0000'});

      });

    },
    /**
     * 
     * @param {type} url
     * @param {type} idFormulario
     * @returns {undefined}
     */
    subida_ajax: function (url, idFormulario) {
      //var data_fomulario = new FormData($('#mega_form')[0]);
      var data_fomulario = new FormData($('#' + idFormulario));
      
      /**
       * nota importante el formulario tiene que estar
       * enctype="multipart/form-data" method="post" accept-charset="utf-8"
       */

      $.ajax({
        url: url,
        type: 'POST',
        data: data_fomulario,
        async: false,
        cache: false,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function (data) {
          console.log(data);
        }
      });

    },
    /**
     * 
     * @param {type} archivo
     * @param {type} id
     * @returns {undefined}
     */
    carga_informacion_imagen: function (archivo, id) {
      if (window.File && window.FileReader && window.FileList && window.Blob) {
       console.log("soporta lectura de archivos");
       console.log(archivo);
       console.log(id);
      } else {
        alert('The File APIs are not fully supported in this browser.');
      }      

      file_id = document.getElementById(id);

      var files = archivo;

      for (var i = 0, f; f = files[i]; i++) {

    
        if (!f.type.match('image.*')) {
          continue;
        }

        var reader = new FileReader();

        // Closure to capture the file information.
        reader.onload = (function (theFile) {
          return function (e) {
            // Render thumbnail.
            var span = document.createElement('span');
            span.innerHTML = ['<img class="thumb" src="', e.target.result, '" title="', escape(theFile.name), '"/>'].join('');
            file_id.insertBefore(span, null);
          };
        })(f);

        // Read in the image file as a data URL.
        reader.readAsDataURL(f);
      }



      // files is a FileList of File objects. List some properties.
      var output = [];
      for (var i = 0, f; f = files[i]; i++) {
        output.push('<li><strong>', escape(f.name), '</strong> (', f.type || 'n/a', ') - ',
                f.size, ' bytes, last modified: ',
                f.lastModifiedDate.toLocaleDateString(), '</li>');
      }
      document.getElementById(id).innerHTML = '<ul>' + output.join('') + '</ul>';

    }
  });



})(jQuery);