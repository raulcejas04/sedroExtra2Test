$(document).ready(function(){
    $('#representacion_personaFisica_cuitCuil').attr('disabled','disabled');
});

$('form').on('submit',function(e){
    /*   $("input").find("[disabled='disabled']").each(function(){
          $(this).attr('disabled',false);
      }); */
      $('#representacion_personaFisica_tipoCuitCuil').attr('disabled',false);
  });
