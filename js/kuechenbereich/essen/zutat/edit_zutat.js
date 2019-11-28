/**
 * 
 */
$(function(){
	function populate_dropdownmenu(){
		$.ajax({
		       url: '../../../utilities/for_ajax_calls/get_list_einheit.php',
		       method: 'post',
		       dataType: 'json',
		       success:function(response){
		           var len = response.length;
		           for(var i = 0; i<len; i++){
		               var id = response[i]['id'];
		               var name = response[i]['name'];
		               $("#input_select0").append("<option value='"+id+"'>"+name+"</option>");
		           }
		           $("#input_select0").val(einheitId);
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
			   url: './save_edit_zutat.php',
			   method: 'POST',
			   data: form_data,
			   success: function(data) {
				   $('form').removeClass('was-validated');
				   alert('Die Zutat wurde erfolgreich aktualisiert');
				   window.location.href="../essen_list.php#zutat";
	            },
			   error: function() {
				   alert('Bei der Aktualisierung der Zutat ist ein Fehler aufgetreten !');
	           }
	         });
		}
    });
});