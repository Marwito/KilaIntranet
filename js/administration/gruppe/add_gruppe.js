/**
 * 
 */
$(function(){
	function populate_dropdownmenu(){
		$.ajax({
			url: '../../utilities/for_ajax_calls/get_list_einrichtung.php',
	       	method: 'post',
	       	dataType: 'json',
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
			   url: 'save_add_gruppe.php',
			   method: 'POST',
			   data: form_data,
			   success: function(data) {
				   alert(data);
				   $('form').removeClass('was-validated');
				   $("form")[0].reset();
	            },
			   error: function() {
				   alert('Beim Hinzufügen der Gruppe ist ein Fehler aufgetreten !');
	           }
	         });
		}
		
    });
});