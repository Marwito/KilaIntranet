/**
 * 
 */
$(function(){
	$("#submit").click(function(event){
		event.preventDefault();
		if ($("form")[0].checkValidity() === false) {
			event.stopPropagation();
			$('form').addClass('was-validated');
		} else {
			var form_data = $("form").serialize();
			$.ajax({
			   url: 'save_edit_ansprechpartner.php',
			   method: 'POST',
			   data: form_data,
			   success: function(data) {
				   $('form').removeClass('was-validated');
				   alert('Der Ansprechpartner wurde erfolgreich aktualisiert');
				   window.location.href="../kueche/edit_kueche.php?id="+kueche;
	            },
			   error: function() {
				   alert('Bei der Aktualisierung des Ansprechpartners ist ein Fehler aufgetreten !');
	           }
	         });
		}
    });
});