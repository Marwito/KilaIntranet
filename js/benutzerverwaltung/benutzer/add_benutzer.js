/**
 * 
 */
$(function(){
	function get_attribute(){
		$.ajax({
	       url: '../../utilities/for_ajax_calls/get_list_position.php',
	       method: 'post',
	       dataType: 'json',
	       success:function(response){
	           var len = response.length;
	           for(var i = 0; i<len; i++){
               	   var id = response[i]['id'];
               	   var name = response[i]['name'];
	        	   if (position == "Leiter") {
	        		   if (name != "Administrator" && name != "Leiter") {
	        			   $("#input_select0").append("<option value='"+id+"'>"+name+"</option>");
	        		   }
	        	   } else {
	        		   $("#input_select0").append("<option value='"+id+"'>"+name+"</option>");
	        	   }
	           }
	       }
		});
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
				$("#custom-fehler2").text(' Bitte stellen Sie sicher, dass das Passwort und seine Wiederholung übereinstimmen !');
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
		if (document.getElementById("input_text6").validity.valueMissing) {
			$("#custom-fehler3").text(' Bitte füllen Sie dieses Feld aus !');
		} else {
			if (!document.getElementById("input_text6").validity.valid) {
				$("#custom-fehler3").text(' Bitte korrigieren Sie die angegebene E-Mail-Adresse !');
			}
		}
	}
	$("#einrichtung_kueche").hide();
	get_attribute();
	
	function checkExistEmail() {
		var email = $("#input_text6").val();
		$.ajax({
			url: '../../utilities/for_ajax_calls/check_exist_email.php',
			method: 'POST',
			data: {email: email},
			success: function(data) {
				if (data === 'exist') {
					alert('Die angegebene E-Mail-Adresse ist einem anderen Benutzer zugeordnet !');
				} else if (data === 'no success') {
					saveAddBenutzer();
				} else {
					alert('während der Überprüfung der E-Mail ist ein Fehler aufgetreten !');
				}
			}
        });
	}
	
	function saveAddBenutzer() {
		var form_data = $("#form1").serialize();
		$.ajax({
			url: './save_add_benutzer.php',
			method: 'POST',
			data: form_data,
			success: function(data) {
				alert('Der Benutzer wurde erfolgreich hinzugefügt');
				$('#form1').removeClass('was-validated');
				$('#modal1').modal({
					keyboard: false,
					focus: false
				});
			},
			error: function() {
				alert('Beim Hinzufügen des Benutzers ist ein Fehler aufgetreten !');
			}
		});
	}
	
	$("#submit").click(function(event) {
		event.preventDefault();
		checkPassword();
		checkEmail();
		if ($("#form1")[0].checkValidity() === false) {
			event.stopPropagation();
			$('#form1').addClass('was-validated');
		} else {
			checkExistEmail();
		}
    });
	
	$("#submit2").click(function(event) {
		event.preventDefault();
		var benutzername = $('#input_text0').val();
		var password = $('#input_passwort0').val();
		var email = $('#input_text6').val();
		$.ajax({
		   url: '../zugang/send_zugangsdaten.php',
		   method: 'POST',
		   data: {benutzername: benutzername, password: password, email: email},
		   success: function(data) {
			   if (data == 'OK') {
				   alert('Die Zugangsdaten wurden erfolgreich gesendet');
			   } else if (data == 'no success')  {
				   alert('Die E-Mail konnte nicht an den Empfänger gesendet werden');
			   } else {
				   alert('Die zum Senden der E-Mail erforderlichen Variablen wurden nicht empfangen');
			   }
			   $("#form1")[0].reset();
			   $('#modal1').modal('hide');
		   },
		   error: function() {
			   alert('Beim Senden der Zugangsdaten ist ein Fehler aufgetreten !');
           }
		});
    });
	
	$("#input_select0").change(function() {
		$("#input_select1").empty().append("<option value=''>Wählen...</option>");
		if($("#input_select0").val() == 3) {
			$("#einrichtung_kueche").show();
			document.getElementById("input_select1").required = true;
			$("#label").text('Küche');
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
						$("#input_select1").append("<option value='"+id+"'>"+name+"</option>");
					}
				}
			});
		} else if($("#input_select0").val() == 4 || $("#input_select0").val() == 5) {
			$("#einrichtung_kueche").show();
			document.getElementById("input_select1").required = true;
			$("#label").text('Einrichtung');
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
						$("#input_select1").append("<option value='"+id+"'>"+name+"</option>");
					}
				}
			});
		} else {
			$("#einrichtung_kueche").hide();
			document.getElementById("input_select1").required = false;
		}
	});
});