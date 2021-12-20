$(document).ready(function(){
    $('#representacion_personaFisica_cuitCuil').attr('disabled','disabled');
});

$('form').on('submit',function(e){
      $('#representacion_personaFisica_cuitCuil').attr('disabled',false);
  });
