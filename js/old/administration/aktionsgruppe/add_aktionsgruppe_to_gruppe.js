/**
 * 
 */
$(function(){
	function populate_dropdownmenu(){
		var aktionsgruppe = $('#aktionsgruppe').val();
		$.ajax({
			url: '../../utilities/for_ajax_calls/get_list_gruppe.php',
	       	method: 'post',
	       	dataType: 'json',
	       	data: {aktionsgruppe : aktionsgruppe},
	       	success:function(response) {
	       		var len = response.length;
	       		for(var i = 0; i<len; i++) {
	       			var id = response[i]['id'];
	       			var name = response[i]['name'];
	       			$("#input_select1").append("<option value='"+id+"'>"+name+"</option>");
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
			   url: 'save_add_aktionsgruppe_to_gruppe.php',
			   method: 'POST',
			   data: form_data,
			   success: function(data) {
				   alert('Die Zuordnung Gruppe-Aktionsgruppe wurde erfolgreich hinzugefügt');
				   $('form').removeClass('was-validated');
				   $("form")[0].reset();
				   $('#input_select1').empty().append('<option selected="selected" value="">Wählen ...</option>');
				   populate_dropdownmenu();
	            },
			   error: function() {
				   alert('Beim Hinzufügen der Zuordnung Gruppe-Aktionsgruppe ist ein Fehler aufgetreten !');
	           }
	         });
		}
    });
});