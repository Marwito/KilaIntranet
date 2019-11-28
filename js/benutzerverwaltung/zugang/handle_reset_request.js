/**
 * 
 */
$(function(){
	function checkPassword(){
		var password = $("#input_passwort0").val();
		var passwordRepetition = $("#input_passwort1").val();
		if (password != '' && passwordRepetition != '') {
			if (password != passwordRepetition) {
				// non empty messages will trigger the invalid status of these fields
				document.getElementById("input_passwort0").setCustomValidity(' ');
				document.getElementById("input_passwort1").setCustomValidity(' ');
				// change the error messages if there is a mismatch
				$("#custom-fehler1").text('');
				$("#custom-fehler2").text(' Bitte stellen Sie sicher, dass das Passwort und die Wiederholung übereinstimmen !');
			} else {
				if (document.getElementById("input_passwort0").validity.patternMismatch) {
					$("#custom-fehler1").text('');
					$("#custom-fehler2").text(' Bitte korrigieren Sie Ihre Eingaben');
				} else {
					// change the status of these fields to valid
					document.getElementById("input_passwort0").setCustomValidity('');
					document.getElementById("input_passwort1").setCustomValidity('');
				}
			}
		} else {
			$("#custom-fehler1").text(' Bitte füllen Sie dieses Feld aus !');
			$("#custom-fehler2").text(' Bitte füllen Sie dieses Feld aus !');
		}
	}
	$("#submit").click(function(event){
		event.preventDefault();
		checkPassword();
		if ($("#form1")[0].checkValidity() === false) {
			event.stopPropagation();
			$('#form1').addClass('was-validated');
		} else {
			var form_data = $("#form1").serialize();
			$.ajax({
			   url: 'reset_process.php',
			   method: 'POST',
			   data: form_data,
			   success: function(data) {
				   $('#form1').removeClass('was-validated');
				   var alertType;
				   if (data == 'Das Passwort wurde erfolgreich zurückgesetzt') {
					   alertType = 'alert-success';
				   } else {
					   alertType = 'alert-danger';
				   }
				   $('#form1')[0].reset();
				   $("#form1 :input").prop("disabled", true);
				   $("#message").html("<div class='alert " + alertType + " alert-dismissible fade show' role='alert'>" + data + "<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>");
				   setTimeout(function() {
					    window.location.href="../../../index.php";
				   }, 6000);
	            },
			   error: function() {
				   alert('während des Zurücksetzens des Passworts ist ein Fehler aufgetreten !');
	           }
			});
		}
    });
});