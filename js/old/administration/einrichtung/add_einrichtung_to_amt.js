/**
 * 
 */
$(function(){
	function populate_dropdownmenu(){
		var amt = $('#amt').val();
		$.ajax({
			url: '../../utilities/for_ajax_calls/get_list_einrichtung.php',
	       	method: 'post',
	       	dataType: 'json',
	       	data: {amt : amt},
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
			   url: 'save_add_einrichtung_to_amt.php',
			   method: 'POST',
			   data: form_data,
			   success: function(data) {
				   alert('Die Zuordrung Einrichtung-Amt wurde erfolgreich hinzugefügt');
				   $('form').removeClass('was-validated');
				   $("form")[0].reset();
				   $('#input_select0').empty().append('<option selected="selected" value="">Wählen ...</option>');
				   populate_dropdownmenu();
	            },
			   error: function() {
				   alert('Beim Hinzufügen der Zuordrung Einrichtung-Amt ist ein Fehler aufgetreten !');
	           }
	         });
		}
		
    });
});