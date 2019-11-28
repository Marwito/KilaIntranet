/**
 * 
 */
$(function(){
	function populate_dropdownmenu(){
		var preiskategorie = $('#preiskategorie').val();
		$.ajax({
			url: '../../utilities/for_ajax_calls/get_list_gruppe.php',
	       	method: 'post',
	       	dataType: 'json',
	       	data: {preiskategorie : preiskategorie},
	       	success:function(response) {
	       		var len = response.length;
	       		for(var i = 0; i<len; i++) {
	       			var id = response[i]['id'];
	       			var name = response[i]['name'];
	       			$("#input_select0").append("<option value='"+id+"'>"+name+"</option>");
	       		}
	       	}
		});
	}
	
	populate_dropdownmenu();
	
	$("#submit").click(function(event){
		event.preventDefault();
		if ($("form")[0].checkValidity() === false) {
			event.stopPropagation();
			$('form').addClass('was-validated');
		} else {
			var form_data = $("form").serialize();
			$.ajax({
			   url: 'save_add_preiskategorie_to_gruppe.php',
			   method: 'POST',
			   data: form_data,
			   success: function(data) {
				   alert('Die Zuordrung Gruppe-Preiskategorie wurde erfolgreich hinzugefügt');
				   $('form').removeClass('was-validated');
				   $("form")[0].reset();
				   $('#input_select0').empty().append('<option selected="selected" value="">Wählen ...</option>');
				   populate_dropdownmenu();
	            },
			   error: function() {
				   alert('Beim Hinzufügen der Zuordrung Gruppe-Preiskategorie ist ein Fehler aufgetreten !');
	           }
	         });
		}
    });
});