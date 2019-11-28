/**
 * 
 */
$(function(){
	function checkEmail(){
		if (document.getElementById("input_text0").validity.valueMissing) {
			$("#custom-fehler1").text(' Bitte füllen Sie dieses Feld aus !');
		} else {
			if (!document.getElementById("input_text0").validity.valid) {
				$("#custom-fehler1").text(' Bitte korrigieren Sie die angegebene E-Mail-Adresse !');
			}
		}
	}
	function sendEmail(email){
		$.ajax({
			   url: 'php/benutzerverwaltung/zugang/send_email_password_reset.php',
			   method: 'POST',
			   data: {email: email},
			   success: function(data) {
				   if (data == 'OK') {
					   alert('Die E-mail wurde erfolgreich gesendet, bitte checken Sie Ihren Posteingang oder Spam');
					   $('#modal1').modal('hide');
					   $('#form2')[0].reset();
				   } else {
					   alert('Die E-Mail konnte nicht gesendet werden');
				   }
	           }
         });
	}
	
	$("#submit1").click(function(event){
		if ($("#form1")[0].checkValidity() === false) {
			event.preventDefault();
			event.stopPropagation();
			$('#form1').addClass('was-validated');
		}
    });
	
	$("#submit2").click(function(event){
		event.preventDefault();
		checkEmail();
		if ($("#form2")[0].checkValidity() === false) {
			event.stopPropagation();
			$('#form2').addClass('was-validated');
		} else {
			var email = $("#input_text0").val();
			$.ajax({
			   url: 'php/utilities/for_ajax_calls/check_exist_email.php',
			   method: 'POST',
			   data: {email: email},
			   success: function(data) {
				   if (data == 'exist') {
					   sendEmail(email);
				   } else if (data == 'no success') {
					   alert('Es gibt keinen Benutzer mit dieser E-Mail-Adresse');
				   } else {
					   alert('Die zu der Überprüfung der E-Mail erforderliche Variable wurde nicht empfangen');
				   }
	           },
			   error: function() {
				   alert('während der Überprüfung der E-Mail ist ein Fehler aufgetreten !');
	           }
            });
		}
    });
});	