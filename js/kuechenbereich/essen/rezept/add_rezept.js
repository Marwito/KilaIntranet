/**
 * 
 */
$(function(){
	function populate_dropdownmenu_zutat(){
		$.ajax({
		       url: '../../../utilities/for_ajax_calls/get_list_zutat.php',
		       method: 'post',
		       dataType: 'json',
		       success:function(response){
		           var len = response.length;
		           for(var i = 0; i<len; i++){
		               var id = response[i]['id'];
		               var name = response[i]['name'];
		               $("#input_select0").append("<option value='"+id+"'>"+name+"</option>");
		           }
		       }
		});
	}
	function populate_dropdownmenu_essenkategorie(){
		$.ajax({
		       url: '../../../utilities/for_ajax_calls/get_list_essenkategorie.php',
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
	}
	function populate_dropdownmenu_speisenart(){
		$.ajax({
		       url: '../../../utilities/for_ajax_calls/get_list_speisenart.php',
		       method: 'post',
		       dataType: 'json',
		       success:function(response){
		           var len = response.length;
		           for(var i = 0; i<len; i++){
		               var id = response[i]['id'];
		               var name = response[i]['name'];
		               $("#input_select2").append("<option value='"+id+"'>"+name+"</option>");
		           }
		       }
		});
	}
	populate_dropdownmenu_zutat();
	populate_dropdownmenu_essenkategorie();
	populate_dropdownmenu_speisenart();
	$("#submit").click(function(event){
		event.preventDefault();
		if ($("form")[0].checkValidity() === false) {
			event.stopPropagation();
			$('form').addClass('was-validated');
		} else {
			var form_data = $("form").serialize();
			$.ajax({
			   url: './save_add_rezept.php',
			   method: 'POST',
			   data: form_data,
			   success: function(data) {
				   alert('Das Rezept wurde erfolgreich hinzugefügt');
				   $('form').removeClass('was-validated');
				   $("form")[0].reset();
	            },
			   error: function() {
				   alert('Beim Hinzufügen des Rezeptes ist ein Fehler aufgetreten !');
	           }
	         });
		}
		
    });
});