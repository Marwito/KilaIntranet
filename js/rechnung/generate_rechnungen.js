/**
 * 
 */
$(function(){
	function rechnungen_generation() {
		var formData = $("form").serialize();
		$.ajax ({
			url: 'rechnungen_generation.php',
			method: 'POST',
			data: formData,
			success: function(data) {
				if (data.indexOf('es wird kein Rechnungslauf') > -1) {
					alert(data);
				} else {
					//alert(data);
					alert('Die Rechnungen wurde erfolgreich erstellt');
					$('form').removeClass('was-validated');
					$("form")[0].reset();
				}
			},
			error: function(data) {
				alert(data);
			}
		});
	}
	
	function check_rechnungen() {
		var formData = $("form").serialize();
		$.ajax ({
			url: '../utilities/for_ajax_calls/check_rechnungen.php',
			method: 'POST',
			data: formData,
			success: function(data) {
				if (data == 0) {
					rechnungen_generation();
				} else if (data == 1) {
					alert('Ein neuer Rechnungslauf kann nicht initiiert werden, da es bisher noch nicht abgeschlossene Rechnungen gibt');
				} else {
					alert('ein Rechnungslauf ist unmöglich, da sich der gegebene Zeitraum mit Zeitraüme der bisherigen Rechnungslaüfe überschneidet');
				}
			},
			error: function() {
				alert('bei der Überprüfung, ob alle vorherigen Rechnungsläufe abgeschlossen sind oder nicht, ist ein fehler aufgetreten !');
			}
		});
	}
	
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
	
	$("#datetimepicker1").on("change.datetimepicker", function (e) {
        $('#datetimepicker2').datetimepicker('minDate', e.date);
    });
	
    $("#datetimepicker2").on("change.datetimepicker", function (e) {
        $('#datetimepicker1').datetimepicker('maxDate', e.date);
    });
    
	$("#zeitraum_von").hide();
	$("#zeitraum_bis").hide();
	$("#customRadio1").prop("checked", true);
	document.getElementById("datetimepicker1").required = false;
	document.getElementById("datetimepicker2").required = false;
	 
    $("input[name='customRadio']").change(function(event) { 
		 if ($("#customRadio1").is(":checked")){
			 $("#zeitraum_von").val('');
			 $("#zeitraum_bis").val('');
			 $("#zeitraum_von").hide();
			 $("#zeitraum_bis").hide();
			 $("#monat").show();
			 $("#jahr").show();
			 document.getElementById("input_select0").required = true;
			 document.getElementById("input_text0").required = true;
			 document.getElementById("datetimepicker1").required = false;
			 document.getElementById("datetimepicker2").required = false;
		 } else {
			 $("#monat").val('');
			 $("#jahr").val('');
			 $("#monat").hide();
			 $("#jahr").hide();
			 $("#zeitraum_von").show();
			 $("#zeitraum_bis").show();
			 document.getElementById("input_select0").required = false;
			 document.getElementById("input_text0").required = false;
			 document.getElementById("datetimepicker1").required = true;
			 document.getElementById("datetimepicker2").required = true;
		 }
	 });
    
	$("#submit").click(function(event) {
		event.preventDefault();
		if ($("form")[0].checkValidity() === false) {
			event.stopPropagation();
			$('form').addClass('was-validated');
		} else {
			check_rechnungen();
		}
    });
});