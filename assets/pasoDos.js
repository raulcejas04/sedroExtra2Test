$('form').on('submit',function(e){
    $('#paso_dos_personaFisica_tipoDocumento').attr('disabled',false);
    $('#paso_dos_personaFisica_cuitCuil').attr('disabled',false);
    $('#paso_dos_personaFisica_tipoCuitCuil').attr('disabled',false);
    $('#paso_dos_personaFisica_nacionalidad').attr('disabled',false);
    $('#paso_dos_personaFisica_sexo').attr('disabled',false);
    $('#paso_dos_personaFisica_estadoCivil').attr('disabled',false);
});
