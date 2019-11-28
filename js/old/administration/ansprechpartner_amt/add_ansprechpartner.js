/**
 * 
 */
$(function(){	
	$("body").on('click', "input[type='checkbox']", function (event) {
		var amt = $('#amt').val();
		if ($('#input_checkbox0').is(':checked')) {
			$.ajax({
				url: '../../utilities/for_ajax_calls/check_exist_ansprechpartner_fuer_rechnung.php',
				method: 'POST',
				data: {amt: amt},
				success: function(data) {
					if (data === 'exist') {
						$(this).prop("checked", false);
						alert('Es gibt bereits einen weiteren Ansprechpartner, der für Rechnungen verantwortlich ist !');
					} else if (data === 'no') {
						$(this).prop("checked", true);
					} else {
						alert('Formularvariable ist ungültig oder wird nicht empfangen');
					}
				},
				error: function() {
					alert('ein Fehler ist aufgetreten !');
				}
	        });
		}
	});
	
	function saveAddAnsprechpartner() {
		var form_data = $("form").serialize();
		$.ajax({
			url: 'save_add_ansprechpartner.php',
			method: 'POST',
			data: form_data,
			success: function(data) {
				alert('Der Ansprechpartner wurde erfolgreich hinzugefügt');
				$('form').removeClass('was-validated');
				$("form")[0].reset();
			},
			error: function() {
				alert('Beim Hinzufügen des Ansprechpartners ist ein Fehler aufgetreten !');
			}
		});
	}
	
	$("#submit").click(function(event){
		event.preventDefault();
		if ($("form")[0].checkValidity() === false) {
			event.stopPropagation();
			$('form').addClass('was-validated');
		} else {
			saveAddAnsprechpartner();
		}
    });
});