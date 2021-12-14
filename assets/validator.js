$(document).ready(function() {
$('input[type=text]').each( 
    function(index){  
        var input = $(this);
        console.log('Type: ' + input.attr('type') + ' | Name: ' + input.attr('name') + ' | Value: ' + input.val());
	        var myClass = $(this).attr("class");
        
	        //es del tipo CUIT
	        if( myClass.indexOf("val-cuit") >= 0 )
	        {

	               input.bind("change blur",(event)=>{  

			    input[0].setCustomValidity("");
			    if (!isValidCUITCUIL(input.val()))
			    {
		                    input[0].setCustomValidity("Cuit/Cuil inválido");
		                    //console.log( input.attr('id'));
		                    console.log($("#err_nueva_solicitud_cuit").html());
                                   $("#err_"+input.attr('id')).html("Cuit/Cuil inválido");
                                   //$("#err_nueva_solicitud_cuit").html("Cuit/Cuil inválido");
                                   $("#err_"+input.attr('id')).css('color','red'); // TODO es provisorio, habría que sacarlo y hacerlo desde CSS
                           } else {
                                   $("#err_"+input.attr('id')).html('');
			    }
		      });
	        

		      input.bind('keydown', ( event ) => {
		            if (event.key === 'ArrowLeft' || event.key === 'ArrowRight') return
		            input.selectionStart = input.textLength;
		            input.selectionEnd = input.textLength;
		            input.val( input.val()[1] && input.val()[2]!=='-' ? input.val().slice(0,2) + '-' + input.val().slice(2) : input.val() );
	                    input.val( input.val()[10] && input.val()[11]!=='-' ? input.val().slice(0,11) + '-' + input.val().slice(11) : input.val() );
        	            if (event.key === 'Backspace' && input.selectionStart === 3 && input.selectionEnd === 3 && input.textLength === 3) {
        	                event.preventDefault();
        	                input.val( input.val().slice(0, -2) );
        	                return;
        	            }
        	            if (event.key === 'Backspace' && input.selectionStart === 12 && input.selectionEnd === 12 && input.textLength === 12) {
           	                event.preventDefault();
        	                input.val( input.val().slice(0, -2) );
        	            }
        	     });
        	     
      	             input.bind('keypress', ( event ) => {
		            if (!validator.isNumeric(event.key) || input.textLength >= 13) event.preventDefault()
        		});
        		
        		
	        
	        }
	        
    }
);


$('input[type=email]').each( 
    function(index){  
        var input = $(this);
        console.log('Type: ' + input.attr('type') + ' | Name: ' + input.attr('name') + ' | Value: ' + input.val());
	        var myClass = $(this).attr("class");



      	        if( myClass.indexOf("val-email") >= 0 )
	        {
			console.log("mail");
	               input.bind("change blur",(event)=>{  

			    input[0].setCustomValidity("");
			    if ( !validator.isEmail(input.val()) )
 			    {
			        input[0].setCustomValidity("Mail invalido");
				console.log(input.attr('id'));
      			        input.val('');
      			        input.focus();
      			        document.getElementById(input.attr('id')).focus();
      			        event.preventDefault();
      			        return false;
			    }
		      });
	        
     
	        }
	}
);

})

function isValidCUITCUIL(cuit) {

    if (cuit.length !== 13)
        return false;
    let rv = false;
    let resultado = 0;
    let cuit_nro = cuit.replace("-", "");
    let codes = "6789456789";
    let verificador = parseInt(cuit_nro[cuit_nro.length - 1]);
    let x = 0;
    while (x < 10) {
        let digitoValidador = parseInt(codes.substring(x, x + 1));
        if (isNaN(digitoValidador))
            digitoValidador = 0;
        let digito = parseInt(cuit_nro.substring(x, x + 1));
        if (isNaN(digito))
            digito = 0;
        let digitoValidacion = digitoValidador * digito;
        resultado += digitoValidacion;
        x++;
    }
    resultado = resultado % 11;
    rv = (resultado === verificador);
    return rv;
}
