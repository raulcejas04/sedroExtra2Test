$(document).ready(function(){
    $('#invitacion_personaFisica_tipoCuitCuil').attr('disabled','disabled');
});

$('form').on('submit',function(e){
  /*   $("input").find("[disabled='disabled']").each(function(){
        $(this).attr('disabled',false);
    }); */
    $('#invitacion_personaFisica_tipoCuitCuil').attr('disabled',false);
});