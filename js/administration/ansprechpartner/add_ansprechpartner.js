/**
 * 
 */
$(function(){	

	function saveAddAnsprechpartner() {
		var form_data = $("form").serialize();
		$.ajax({
			url: 'save_add_ansprechpartner.php',
			method: 'POST',
			data: form_data,
			success: function(data) {
				alert(data);
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