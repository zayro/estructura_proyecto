/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


(function(){
 
/* código */
$.fn.extend({
enrojo: function(){
 
return this.each(function(){
 
$(this).css({ 'background-color' : '#ff0000'  });
 
});
 
}
 });
})(jQuery);