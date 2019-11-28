/**
 * 
 */
$(function(){
	function get_attribute_position(){
		$.ajax({
	       url: '../../utilities/for_ajax_calls/get_list_position.php',
	       method: 'post',
	       dataType: 'json',
	       success:function(response) {
	    	   if (positionId == 5) {
	    		   $("#input_position").append("<option value='5' selected>Leiter</option>");
	    	   } else {
	    		   var len = response.length;
		           for(var i = 0; i<len; i++){
		               var id = response[i]['id'];
		               var name = response[i]['name'];
		               if (position == "Leiter") {
	            		   if (name != "Administrator" && name != "Leiter") {
	            			   $("#input_position").append("<option value='"+id+"'>"+name+"</option>");
		        		   }
		        	   } else {
		        		   $("#input_position").append("<option value='"+id+"'>"+name+"</option>");
		        	   }
		           }
		           $("#input_position").val(positionId);
	    	   }
	    	   get_attribute_einrichtung_kueche();
	       }
		});
	}
	
	function get_attribute_einrichtung_kueche(){
		if ($("#input_position").val() == 3) {
			$("#label").text('Küche');
			$("#einrichtung_kueche").show();
			document.getElementById("input_einrichtung_kueche").required = true;
			$("#nachricht").text('Bitte wählen Sie eine Küche aus!');
			$.ajax({
			       url: '../../utilities/for_ajax_calls/get_list_kueche.php',
			       method: 'post',
			       dataType: 'json',
			       success:function(response){
			           var len = response.length;
			           for(var i = 0; i<len; i++){
			               var id = response[i]['id'];
			               var name = response[i]['name'];
			               $("#input_einrichtung_kueche").append("<option value='"+id+"'>"+name+"</option>");
			           }
			           if (kueche != -1) {
			        	   $("#input_einrichtung_kueche").val(kueche);
			           }
			       }
			});
		} else if($("#input_position").val() == 4 || $("#input_position").val() == 5) {
			$("#label").text('Einrichtung');
			$("#einrichtung_kueche").show();
			document.getElementById("input_einrichtung_kueche").required = true;
			$("#nachricht").text('Bitte wählen Sie eine Einrichtung aus!');
			var value;
			if (position == "Administrator") {
				value = '1';
			} else {
				value = '0';
			}
			$.ajax({
			       url: '../../utilities/for_ajax_calls/get_list_einrichtung.php',
			       method: 'post',
			       data: {value: value},
			       dataType: 'json',
			       success:function(response){
			           var len = response.length;
			           for(var i = 0; i<len; i++){
			               var id = response[i]['id'];
			               var name = response[i]['name'];
			               $("#input_einrichtung_kueche").append("<option value='"+id+"'>"+name+"</option>");
			           }
			           if (einrichtung != -1) {
			        	   $("#input_einrichtung_kueche").val(einrichtung);
			           }
			       }
			});
		} else {
			document.getElementById("input_einrichtung_kueche").required = false;
			$("#einrichtung_kueche").hide();
		}
	}
	
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
	
	function checkEmail(){
		if (document.getElementById("input_email").validity.valueMissing) {
			$("#custom-fehler3").text(' Bitte füllen Sie dieses Feld aus !');
		} else {
			if (!document.getElementById("input_email").validity.valid) {
				$("#custom-fehler3").text(' Bitte korrigieren Sie die angegebene E-Mail-Adresse !');
			}
		}
	}
	
	function checkExistEmail(email) {
		$.ajax({
			url: '../../utilities/for_ajax_calls/check_exist_email.php',
			method: 'POST',
			data: {email: email},
			success: function(data) {
				if (data === 'exist') {
					alert('Die angegebene E-Mail-Adresse ist einem anderen Benutzer zugeordnet !');
				} else if (data === 'no success') {
					saveEditBenutzer();
				} else {
					alert('während der Überprüfung der E-Mail ist ein Fehler aufgetreten !');
				}
			}
        });
	}

	function saveEditBenutzer() {
		var form_data = $("#form1").serialize();
		$.ajax({
		   url: 'save_edit_benutzer.php',
		   method: 'POST',
		   data: form_data,
		   success: function(data) {
			   $('#form1').removeClass('was-validated');
			   alert('Der Benutzer wurde erfolgreich aktualisiert');
			   window.location.href="./benutzer_list.php";
		   },
		   error: function() {
			   alert('Bei der Aktualisierung des Benutzers ist ein Fehler aufgetreten !');
           }
		});
	}

	get_attribute_position();
	var oldEmail = $("#input_email").val();
	
	$("#submit").click(function(event){
		event.preventDefault();
		checkEmail();
		if ($("#form1")[0].checkValidity() === false) {
			event.stopPropagation();
			$('#form1').addClass('was-validated');
		} else {
			var newEmail = $("#input_email").val();
			if (newEmail != oldEmail) {
				checkExistEmail(newEmail);
			} else {
				saveEditBenutzer();
			}
		}
    });
	
	$("#submit2").click(function(event){
		event.preventDefault();
		checkPassword();
		if ($("#form2")[0].checkValidity() === false) {
			event.stopPropagation();
			$('#form2').addClass('was-validated');
		} else {
			var form_data = $("#form2").serialize();
			$.ajax({
			   url: '../zugang/save_edit_passwort.php',
			   method: 'POST',
			   data: form_data,
			   success: function(data) {
				   $('#form2').removeClass('was-validated');
				   alert('Das Passwort wurde erfolgreich aktualisiert');
				   $('#contact-benutzer').prop("disabled", false);
				   $("#submit2").prop("disabled", true);
				   $(".contact-benutzen-tip").text(" Bitte klicken Sie auf den Button 'Schicken', um das neue Passwort an den Benutzer zu schicken");
	            },
			   error: function() {
				   alert('während des Zurücksetzens des Passworts ist ein Fehler aufgetreten !');
	           }
			});
		}
    });
	
	$("#contact-benutzer").click(function(event){
		event.preventDefault();
		var email = $('#input_email').val();
		var password = $('#input_passwort0').val();
		$.ajax({
		   url: '../zugang/send_new_password.php',
		   method: 'POST',
		   data: {email: email, password: password},
		   success: function(data) {
			   if (data == 'OK') {
				   alert('Das Passwort wurde erfolgreich gesendet');
			   } else if (data == 'no success')  {
				   alert('Das Passwort konnte nicht an den Empfänger gesendet werden');
			   } else {
				   alert('Die zum Senden des Passworts erforderlichen Variablen wurden nicht empfangen');
			   }
			   $("#form2")[0].reset();
			   $('#modal1').modal('hide');
			   $(".contact-benutzen-tip").text('');
			   $('#contact-benutzer').prop("disabled", true);
			   $("#submit2").prop("disabled", false);
		   },
		   error: function() {
			   alert('Beim Senden des Passworts ist ein Fehler aufgetreten !');
           }
		});
    });
	
	$("#input_position").change(function() {
		$("#input_einrichtung_kueche").empty().append("<option value=''>Wählen...</option>");
		get_attribute_einrichtung_kueche();
	});
});