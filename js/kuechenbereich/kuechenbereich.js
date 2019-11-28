$(function(){
	var essenkategorien = [];
	var einrichtungen = [];
	var essenSumme = [];

	var table = $('#tab1').DataTable({
        "language": {
        	"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/German.json"
        },
        "columnDefs": [
        	{ "orderable": false, "targets": "_all" },
            { "width": '1%', "targets": 0 }
          ],
        "aaSorting": [],
        "lengthChange": false,
        "paging": false,
        "info": false,
        "searching": false
	});
	
	function mondayOfWeek(date) {
	    var diff = date.getDate() - date.getDay() + (date.getDay() === 6 ? 8 : 1);
	    var temp = new Date(date.setDate(diff));
	    var string = temp.getDate();
	    if(parseInt(string) < 10) {
	    	string = "0"+string;
	    }
	    var tempMonth = temp.getMonth()+1;
	    if(parseInt(tempMonth) < 10) {
	    	string = string + ".0" + (temp.getMonth()+1) + "." + temp.getFullYear();
	    } else {
	    	string = string+"."+(temp.getMonth()+1)+"."+temp.getFullYear();
	    }
	    return string;
	 }
	
	function fridayOfWeek(date) {
		var diff = date.getDate() - date.getDay() + (date.getDay() === 6 ? 12 : 5);
		var temp = new Date(date.setDate(diff));
	    var string = temp.getDate();
	    if(parseInt(string) < 10) {
	    	string = "0"+string;
	    }
	    var tempMonth = temp.getMonth()+1;
	    if(parseInt(tempMonth) < 10) {
	    	string = string + ".0" + (temp.getMonth()+1) + "." + temp.getFullYear();
	    } else {
	    	string = string+"."+(temp.getMonth()+1)+"."+temp.getFullYear();
	    }
	    return string;
	}
	
	function getBestellungenOfZeitraum(start, ende, essenkategorie, einrichtung) {
		if($('#input_select1').val()!='-1') {
			var aktionsgruppe = $('#input_select1').val();
		} else {
			var aktionsgruppe = null;
		}
		$.ajax({
			url: '../utilities/for_ajax_calls/get_anzahl_bestellungen_in_zeitraum.php',
			method: 'POST',
			data: {essenkategorie: essenkategorie, einrichtung: einrichtung, start: start, ende: ende, aktionsgruppe: aktionsgruppe},
			success: function(response) {
				updateEntry(response, essenkategorie, einrichtung);
				getDauerbestellungenOfZeitraum(start, ende, essenkategorie, einrichtung);
			}
		});
	}
	
	function getDauerbestellungenOfZeitraum(start, ende, essenkategorie, einrichtung) {
		if($('#input_select1').val()!='-1') {
			var aktionsgruppe = $('#input_select1').val();
		} else {
			var aktionsgruppe = null;
		}
		$.ajax({
			url: '../utilities/for_ajax_calls/get_anzahl_dauerbestellungen_in_zeitraum.php',
			method: 'POST',
			data: {essenkategorie: essenkategorie, einrichtung: einrichtung, start: start, ende: ende, aktionsgruppe: aktionsgruppe},
			success: function(response) {
				updateEntryDauerbesteller(response, essenkategorie, einrichtung);
			}
		});
	}
	
	function updateEntry(value, essenkategorie, einrichtung) {
		var cell = table.cell($('#ess'+essenkategorie+'einr'+einrichtung));
		table.cell($('#ess'+essenkategorie+'einr'+einrichtung)).data(value);
		essenSumme[einrichtung] = parseInt(essenSumme[einrichtung]) + parseInt(value);
	}
	
	function updateEntryDauerbesteller(value, essenkategorie, einrichtung) {
		var wert = parseInt(table.cell($('#ess'+essenkategorie+'einr'+einrichtung)).data());
		wert = wert + parseInt(value);
		table.cell($('#ess'+essenkategorie+'einr'+einrichtung)).data(wert).draw();
		essenSumme[einrichtung] = parseInt(essenSumme[einrichtung]) + parseInt(value);
		displaySum(einrichtung);
	}
	
	function displaySum(einrichtung) {
		table.cell($('#summe'+einrichtung)).data(essenSumme[einrichtung]).draw();
		table.columns.adjust().draw();
	}
	
	function get_essenkategorien(){
		$.ajax({
			url: '../utilities/for_ajax_calls/get_list_essenkategorie.php',
			method: 'post',
			async: false,
			dataType: 'json',
			success:function(response){
				var len = response.length;
				for(var i = 0; i<len; i++){
					var id = response[i]['id'];
					essenkategorien.push(id);
				}
			}, 
		});
	}
	
	function get_einrichtungen(){
		$.ajax({
			url: '../utilities/for_ajax_calls/get_list_einrichtung.php',
			method: 'post',
			async: false,
			data: { kuechenId: kuechenId },
			dataType: 'json',
			success:function(response){
				var len = response.length;
				for(var i = 0; i<len; i++){
					var id = response[i]['id'];
					einrichtungen.push(id);
				}
			}
		});
	}
	
	var hash = window.location.hash;
	hash && $('ul.nav a[href="' + hash + '"]').tab('show');
	
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
    
    get_essenkategorien();
    get_einrichtungen();
    var time = new Date();
    $("#datetimepicker1").val(mondayOfWeek(time));
    $("#datetimepicker2").val(fridayOfWeek(time));
    
    $("#input_select1").change(function() {
		if ($("form")[0].checkValidity() === false) {
			event.stopPropagation();
			$('form').addClass('was-validated');
		} else {
			for(var i=0; i<essenkategorien.length; i++) {
				for(var j=0; j<einrichtungen.length; j++) {
					essenSumme[einrichtungen[j]] = 0;
					getBestellungenOfZeitraum($("#datetimepicker1").val(), $("#datetimepicker2").val(), essenkategorien[i], einrichtungen[j])
				}
			}
		}
    });
    
    $("#submitAnzeigen").click(function(event){
		event.preventDefault();
		if ($("form")[0].checkValidity() === false) {
			event.stopPropagation();
			$('form').addClass('was-validated');
		} else {
			for(var i=0; i<essenkategorien.length; i++) {
				for(var j=0; j<einrichtungen.length; j++) {
					essenSumme[einrichtungen[j]] = 0;
					getBestellungenOfZeitraum($("#datetimepicker1").val(), $("#datetimepicker2").val(), essenkategorien[i], einrichtungen[j])
				}
			}
		}
    });
    $("#submitAnzeigen").click();
});