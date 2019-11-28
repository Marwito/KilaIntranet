$(function(){
	var tablecount = 2;
	var table1;
	var essentable;
	var time = new Date();
    $("#datetimepicker1").val(mondayOfWeek(time));
	function initialization_tab1() {
		table1 = $('#table1').DataTable({
	        "language": {
	        	"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/German.json"
	        },
	        "columnDefs": [
	            { "orderable": false, "targets": [0,1] },
	            { "width": '1%', "targets": [0,1] }
	          ],
	        "aaSorting": [],
	        "lengthChange": false,
	        "paging": false,
	        "info": false,
	        "autoWidth": false
		});
	}
	
	var test1 = false;
	var test2 = false;
    var hash = window.location.hash;
	hash && $('ul.nav a[href="' + hash + '"]').tab('show');
	
	if((hash == '' || hash == '#kinderliste') && test1 == false) {
		initialization_tab1();
		test1 = true;
	}
	
	$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
		var target = e.target.id;
		if (target =='kinderlisten-tab') {
			if(!test1) {
				initialization_tab1();
				test1 = true;
			} else {
				table1.responsive.recalc();
			}
		} else if (target =='essenslisten-tab') {
			if(!test2) {
				$("#submitAnzeigen").click();
				test2 = true;
			} else {
				essentable.responsive.recalc();
			}
		}
	});
	
	function get_einrichtungen(){
		$.ajax({
			url: '../utilities/for_ajax_calls/get_list_einrichtung.php',
			method: 'post',
			dataType: 'json',
			success:function(response){
				var len = response.length;
				for(var i = 0; i<len; i++){
					var id = response[i]['id'];
					var name = response[i]['name'];
					$('#input_einrichtung').append("<option value='"+id+"'>"+name+"</option>");
				}
			}
		});
	}
	
	function get_gruppe(){
		$.ajax({
			url: '../utilities/for_ajax_calls/get_list_gruppe.php',
			method: 'post',
			data: { einrichtung: $('#input_einrichtung').val() },
			dataType: 'json',
			success:function(response){
				var len = response.length;
				for(var i = 0; i<len; i++){
					var id = response[i]['id'];
					var name = response[i]['name'];
					$('#input_einrichtung').append("<option value='"+id+"'>"+name+"</option>");
				}
			}
		});
	}
	
	if($('#input_einrichtung').val() != -1) {
		get_einrichtungen();
	} else {
		get_gruppe();
	}
	
	$('#input_einrichtung').change(function() {
		$('#input_gruppe').empty().append("<option value='-1'>Wählen...</option>");
		$('#input_aktionsgruppe').empty().append("<option value='-1'>Wählen...</option>");
		$.ajax({
			url: '../utilities/for_ajax_calls/get_list_gruppe.php',
			method: 'post',
			data: { einrichtung: $('#input_einrichtung').val() },
			dataType: 'json',
			success:function(response){
				var len = response.length;
				for(var i = 0; i<len; i++){
					var id = response[i]['id'];
					var name = response[i]['name'];
					$('#input_gruppe').append("<option value='"+id+"'>"+name+"</option>");
				}
			}
		});
	});
	
	$('#input_gruppe').change(function() {
		$('#input_aktionsgruppe').empty().append("<option value='-1'>Wählen...</option>");
		$.ajax({
			url: '../utilities/for_ajax_calls/get_list_aktionsgruppe.php',
			method: 'post',
			data: { gruppe: $('#input_gruppe').val() },
			dataType: 'json',
			success:function(response){
				var len = response.length;
				for(var i = 0; i<len; i++){
					var id = response[i]['id'];
					var name = response[i]['name'];
					$('#input_aktionsgruppe').append("<option value='"+id+"'>"+name+"</option>");
				}
			}
		});
	});
	
	$('#datetimepicker1').datetimepicker({
        locale: 'de',
        format: 'L',
        useCurrent: false,
        buttons: {showClose: true }
    });
	
	$('#datetimepicker2').datetimepicker({
        locale: 'de',
        format: 'L',
        useCurrent: false,
        buttons: {showClose: true }
    });
	
	$('#datetimepicker3').datetimepicker({
        locale: 'de',
        format: 'L',
        useCurrent: false,
        buttons: {showClose: true }
    });
	
	$("#datetimepicker2").on("change.datetimepicker", function (e) {
        $('#datetimepicker3').datetimepicker('minDate', e.date);
    });
	
    $("#datetimepicker3").on("change.datetimepicker", function (e) {
        $('#datetimepicker2').datetimepicker('maxDate', e.date);
    });
	
	function mondayOfWeek(date) {
	    var diff = date.getDate() - date.getDay() + (date.getDay() === 6 ? 8 : 1);
	    var temp = new Date(date.setDate(diff));
	    var string = temp.getDate();
	    if(parseInt(string) < 10) {
	    	string = "0"+string;
	    }
	    var month = temp.getMonth()+1;
	    if(month < 10) {
	    	month = "0"+month;
	    }
	    string = string+"."+month+"."+temp.getFullYear();
	    return string;
	 }
	
	$('#datetimepicker1').focusout(function() {
		$(this).datetimepicker('hide');
	});
	
	$('#datetimepicker2').focusout(function() {
		$(this).datetimepicker('hide');
	});
	
	$('#datetimepicker3').focusout(function() {
		$(this).datetimepicker('hide');
	});

    function stringToDate(string) {
    	var year = string.substr(-4);
    	var month = string.substr(3, 2);
    	var day = string.substr(0, 2);
    	var date = new Date(year+"-"+month+"-"+day);
    	return date;
    }
    
    $("#submitAnzeigen").click(function(event){
		event.preventDefault();
		if ($("#form")[0].checkValidity() === false) {
			event.stopPropagation();
			$('#form').addClass('was-validated');
		} else {
			var datum = mondayOfWeek(stringToDate($("#datetimepicker1").val()));
			$.ajax({
				url: './kind/get_bestellung_table.php',
			    method: 'post',
			    data: { tablecount: tablecount, datum: datum }, 
			    success:function(response){
			    	$("#show").html(response);
			    	essentable = $('#table'+tablecount).DataTable({
				        "language": {
				        	"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/German.json"
				        },
				        "columnDefs": [
				            { "orderable": false, "targets": 0 },
				            { "width": '1%', "targets": 0 }
				          ],
				        "aaSorting": [],
				        "lengthChange": false,
				        "paging": false,
				        "info": false,
				        "autoWidth": false
					});
			    	tablecount++;
			    }
			});
	    	
		}
    });
    
    $("#submitAbbestellen").click(function(event){
		event.preventDefault();
		if ($('#datetimepicker2').val() == '' || $('#datetimepicker3').val() == '' || $('#input_einrichtung').val() == '' || $('#input_grund').val() == '') {
			event.stopPropagation();
			$('#formAbbestellung').addClass('was-validated');
		} else {
			$.ajax({
				url: '../utilities/for_ajax_calls/abbestellung_gruppe.php',
			    method: 'post',
			    data: { start: $('#datetimepicker2').val(), ende: $('#datetimepicker3').val(), einrichtung: $('#input_einrichtung').val(), gruppe: $('#input_gruppe').val(), aktionsgruppe: $('#input_aktionsgruppe').val(), grund: $('#input_grund').val() }, 
			    success:function(response){
			    	alert(response);
			    	$('#formAbbestellung').reset();
			    },
			    error:function(response) {
			    	alert("Fehler beim abbestellen: "+response);
			    }
			});
		}
    });
    
    $(".btn-warning").click(function(event){
    	event.preventDefault();
		var id = $(this).parents("tr").attr("id");
		$.ajax({
			url: '../utilities/for_ajax_calls/change_sessionvariable_kind.php',
		    method: 'post',
		    data: { kind: id }, 
		    success:function(){
		    	window.location.replace(url+"/php/elternbereich/elternbereich.php")
		    }
		});
    });
});