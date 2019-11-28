/**
 * 
 */
$(function(){
	var test = $('#input_checkbox0').is(':checked');
	$("body").on('click', "input[type='checkbox']", function (event) {
		if (test == false) {
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
	
	function saveEditAnsprechpartner() {
		var form_data = $("form").serialize();
		$.ajax({
		   url: 'save_edit_ansprechpartner.php',
		   method: 'POST',
		   data: form_data,
		   success: function(data) {
			   $('form').removeClass('was-validated');
			   alert('Der Ansprechpartner wurde erfolgreich aktualisiert');
			   window.location.href="../amt/edit_amt.php?id="+amt;
		   },
		   error: function() {
			   alert('Bei der Aktualisierung des Ansprechpartners ist ein Fehler aufgetreten !');
		   }
		});
	}
	
	$("#submit").click(function(event){
		event.preventDefault();
		if ($("form")[0].checkValidity() === false) {
			event.stopPropagation();
			$('form').addClass('was-validated');
		} else {
			saveEditAnsprechpartner();
		}
    });
});