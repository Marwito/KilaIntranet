$(function(){	
	$('[data-toggle="tooltip"]').tooltip();
	
	function initialization_tableRechnung() {
		$('#tableRechnung').DataTable({
			"language": {
				"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/German.json"
			},
			"columnDefs": [
				{ "orderable": false, "targets": [0,3] },
				{ "width": '1%', "targets": [0,3] }
				],
				"aaSorting": [],
				"lengthChange": false,
				"paging": false,
				"info": false,
				"searching": true,
				"autoWidth": false
		});
	}
	
	function get_attribute(){
		$.ajax({
			url: '../utilities/for_ajax_calls/get_list_essenkategorie.php',
			method: 'post',
			dataType: 'json',
			success:function(response){
				var len = response.length;
				for(var i = 0; i<len; i++){
					var id = response[i]['id'];
					var name = response[i]['name'];
					$("#input_select0").append("<option value='"+id+"'>"+name+"</option>");
					$("#input_select2").append("<option value='"+id+"'>"+name+"</option>");
				}
			}
		});
	}
	
	var hash = window.location.hash;
	hash && $('ul.nav a[href="' + hash + '"]').tab('show');
	
	$('#modalEssenskategorie').modal({
		show: false
	});
	
	$('#modalEssenskategorieZeitraum').modal({
		show: false
	});
	
	get_attribute();
	initialization_tableRechnung();
	
	while(weeksOfThisMonth >= 0)
	{
		if(window['resultMo'+weeksOfThisMonth])		//window[] für variable variablennamen
		{
			$("#nichtBestelltMo"+weeksOfThisMonth).hide();
		} else {
			$("#bestelltMo"+weeksOfThisMonth).hide();
		}
		if(window['resultDi'+weeksOfThisMonth])
		{
			$("#nichtBestelltDi"+weeksOfThisMonth).hide();
		} else {
			$("#bestelltDi"+weeksOfThisMonth).hide();
		}
		if(window['resultMi'+weeksOfThisMonth])
		{
			$("#nichtBestelltMi"+weeksOfThisMonth).hide();
		} else {
			$("#bestelltMi"+weeksOfThisMonth).hide();
		}
		if(window['resultDo'+weeksOfThisMonth])
		{
			$("#nichtBestelltDo"+weeksOfThisMonth).hide();
		} else {
			$("#bestelltDo"+weeksOfThisMonth).hide();
		}
		if(window['resultFr'+weeksOfThisMonth])
		{
			$("#nichtBestelltFr"+weeksOfThisMonth).hide();
		} else {
			$("#bestelltFr"+weeksOfThisMonth).hide();
		}
		weeksOfThisMonth--;
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
    
    $("#input_select1").change(function() {
    	$.ajax({
			url: '../utilities/for_ajax_calls/change_sessionvariable_kind.php',
			method: 'post',
			data: { kind: $("#input_select1").val() },
			success:function(response){
				location.reload();
				}
		});
	});
    
	$("#submitBestellen").click(function(event){
		event.preventDefault();
		var frist = $('#datetimepicker1').val();
		var temp = frist.slice(-4);
		temp = temp.concat("-", frist.slice(3,5), "-", frist.slice(0,2));
		frist = new Date(temp);
		var fristStunden = parseInt(fristZeit.slice(0, 2));
		var fristMinuten = parseInt(fristZeit.slice(3, 5));
		frist = new Date(frist.setDate(frist.getDate() - fristTag));
		frist = new Date(frist.setHours(fristStunden, fristMinuten, 0, 0));
		frist = frist.getTime();
		var now = Date.now();
		if ($("form")[0].checkValidity() === false) {
			event.stopPropagation();
			$('form').addClass('was-validated');
		} else if(now < frist || admin) {
			$('#modalEssenskategorieZeitraum').modal('show');
			$('#modalSaveZeitraum').click(function(event){
				event.preventDefault();
				if ($("#essenskategorieFormZeitraum")[0].checkValidity() === false) {
					event.stopPropagation();
					$('#essenskategorieFormZeitraum').addClass('was-validated');
				} else {
					var form_data = $("form").serialize();
					form_data = form_data.concat("&essenkategorie="+$('#input_select2').val());
					$.ajax({
						url: './bestellungen/zeitraum_bestellen.php',
						method: 'POST',
						data: form_data,
						success: function(data) {
							$('#modalEssenskategorieZeitraum').modal('hide');
							alert(data);
							$('form').removeClass('was-validated');
							$("form")[0].reset();
					   		location.reload();
						},
						error: function() {
							alert('Beim Bestellen ist ein Fehler aufgetreten!');
						}
					});
				}
			});
		} else {
			alert("Bestellungen konnten nicht durchgeführt werden, Frist ist bei mindestens einer Bestellung schon abgelaufen!");
		}
	});
	
	$("#submitAbbestellen").click(function(event){
		event.preventDefault();
		var frist = $('#datetimepicker1').val();
		var temp = frist.slice(-4);
		temp = temp.concat("-", frist.slice(3,5), "-", frist.slice(0,2));
		frist = new Date(temp);
		var fristStunden = parseInt(fristZeit.slice(0, 2));
		var fristMinuten = parseInt(fristZeit.slice(3, 5));
		frist = new Date(frist.setDate(frist.getDate() - fristTag));
		frist = new Date(frist.setHours(fristStunden, fristMinuten, 0, 0));
		frist = frist.getTime();
		var now = Date.now();
		if ($("form")[0].checkValidity() === false) {
			event.stopPropagation();
			$('form').addClass('was-validated');
		} else if(now < frist || admin) {
			var grund = prompt('Möchten Sie das Essen wirklich abbestellen?\nGrund: ', '');
			if(grund != null && grund != ''){
				var form_data = $("form").serialize();
				form_data = form_data.concat("&grund="+grund);
				$.ajax({
					url: './bestellungen/zeitraum_abbestellen.php',
					method: 'POST',
					data: form_data,
					success: function(data) {
						alert(data);
						$('form').removeClass('was-validated');
						$("form")[0].reset();
						location.reload();
					},
					error: function() {
						alert('Beim Abbestellen ist ein Fehler aufgetreten!');
					}
				});
			} else {
				alert('Essen nicht abbestellt. Grund darf nicht leer sein!');
			}
		} else {
			alert("Abbestellungen konnten nicht durchgeführt werden, Frist ist bei mindestens einer Abbestellung schon abgelaufen!");
		}
	});
	
	$(".btn-danger").click(function(event){
		event.preventDefault();
		var date = $(this).parents("div.card-body").attr("id");
		var divAbbestellen = $(this).parents("div").attr("id");
		var dayOfWeek = divAbbestellen.slice(8, 10);
		var variableDate = date.replace(/-/g, "_");
		var dateMilliseconds = new Date(date);
		dateMilliseconds = dateMilliseconds.getTime();
		if(window['abbestellung'+variableDate] == 1 && dateMilliseconds >= window['essenstart'+variableDate] && dateMilliseconds <= window['essenende'+variableDate]) {
			var link = 'add_abbestellung.php';
		} else {
			var link = 'deactivate_bestellung.php';
		}
		var grund = prompt('Möchten Sie das Essen wirklich abbestellen?\nGrund: ', '');
		if(grund != null && grund !='')
		{
			$.ajax({
				url: './bestellungen/'+link,
				method: 'POST',
				data: {id: kindId, datum: date, frist: window['abmeldefrist'+variableDate], grund: grund, essenkategorie: window['essenkat'+variableDate]},
				error: function() {
					alert('Beim Abbestellen des Essens ist ein Fehler aufgetreten!');
				},
				success: function(data) {
					$('#'+divAbbestellen).hide();
				    $('#'+divAbbestellen.replace("bestellt", "nichtBestellt")).show();
				    alert(data);
				}
			 });
		} else {
			alert("Essen nicht abbestellt. Grund darf nicht leer sein!");
		}
	});
	
	$(".btn-success").click(function(event){
		event.preventDefault();
		var date = $(this).parents("div.card-body").attr("id");
		var divBestellen = $(this).parents("div").attr("id");
		var divAbbestellen = divBestellen.replace("nichtBestellt", "bestellt");
		var dayOfWeek = divBestellen.slice(13, 15);
		var variableDate = date.replace(/-/g, "_");
		var pToChange = $("#"+divAbbestellen).children("p");
		var dateMilliseconds = new Date(date);
		dateMilliseconds = dateMilliseconds.getTime();
		if(window['abbestellung'+variableDate] == 1 && dateMilliseconds >= window['essenstart'+variableDate] && dateMilliseconds <= window['essenende'+variableDate]) {
			if(confirm('Möchten Sie das Essen wirklich bestellen?')) {
				$.ajax({
					url: './bestellungen/deactivate_abbestellung.php',
					method: 'POST',
					data: {id: kindId, datum: date, frist: window['abmeldefrist'+variableDate]},
					error: function() {
						alert('Beim Bestellen des Essens ist ein Fehler aufgetreten!');
					},
					success: function(data) {
						$('#'+divBestellen).hide();
						$('#'+divBestellen.replace("nichtBestellt", "bestellt")).show();
						alert(data);
					}
				});
			}
		} else {
			$('#modalEssenskategorie').modal('show');
			var name;
			$('#modalSave').click(function(event){
				if($('#'+divAbbestellen).is(":hidden")) {
					event.preventDefault();
					if ($("#essenskategorieForm")[0].checkValidity() === false) {
						event.stopPropagation();
						$('#essenskategorieForm').addClass('was-validated');
					} else {
						var essenskategorie = $('#input_select0').val();
						$.ajax({
							url: '../utilities/for_ajax_calls/get_essenkategorie_by_id.php',
							method: 'post',
							data: {id: essenskategorie},
							datatype: 'text',
							success:function(data){
								name = data;
							}
						});
						$.ajax({
							url: './bestellungen/add_bestellung.php',
							method: 'POST',
							data: {id: kindId, datum: date, frist: window['abmeldefrist'+variableDate], essenkategorie: essenskategorie},
							error: function() {
								$('#modalEssenskategorie').modal('hide');
								alert('Beim Bestellen des Essens ist ein Fehler aufgetreten!');
							},
							success: function(data) {
								$('#'+divBestellen).hide();
								$('#'+divAbbestellen).show();
								$('#modalEssenskategorie').modal('hide');
								pToChange.html("Essen bestellt: "+name);
								alert(data);
							}
						});
					}
				}
			});
		}
	});
});