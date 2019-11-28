/**
 * 
 */
$(function(){

    $('#datetimepicker1').datetimepicker({
        locale: 'de',
        format: 'L',
        useCurrent: false,
        buttons: {showClose: true }
    });
	
	$('#datetimepicker1').focusout(function() {
		$(this).datetimepicker('hide');
	});
	
    $('#datetimepicker2').datetimepicker({
        locale: 'de',
        format: 'L',
        useCurrent: false,
        buttons: {showClose: true }
    });
	
	$('#datetimepicker2').focusout(function() {
		$(this).datetimepicker('hide');
	});
	
	/*$("#form").on('submit',(function(event) {
		event.preventDefault();
		if ($("form").checkValidity() === false) {
			event.stopPropagation();
			$('form').addClass('was-validated');
		}
    }));*/
	
	/*$("#submit").click(function(event) {
		event.preventDefault();
		event.preventDefault();
		if ($("form")[0].checkValidity() === false) {
			event.stopPropagation();
			$('form').addClass('was-validated');
		} else {

			//var form_data = $("form").serialize();

			$.ajax({
			   url: 'datev_export.php',
			   method: 'POST',
			   data: form_data,
			   type: 'json',
			   success: function(data) {
					//alert(data);
				   if (data.error) {
					   alert(data.error.msg);
				   } else {
					   $('wizardForm').removeClass('was-validated');
					   $("form")[0].reset();
				   }
	            },
				error: function() {
					alert('Bei DATV-Export ist ein Fehler aufgetreten!');
		        }
	        });
		}
    });*/
});