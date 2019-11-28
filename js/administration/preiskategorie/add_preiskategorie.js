/**
 * 
 */
$(function(){
	var selectedGruppeId = "";
	var table_gruppen;
	var table_select_gruppe;
	
	function init_Datatable_Gruppen() {
		table_gruppen = $('#table_gruppen').DataTable({
	        "language": {
	        	"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/German.json"
	        },
	        "columnDefs": [
	            { "orderable": false, "targets": [0,1] },
	            { "width": '1%', "targets": [0,1] },
	            { "targets": [0], "data" : "First" },
	            { "targets": [1], "data" : "Link" },
	            { "targets": [2], "data" : "Name" },
	            { "targets": [3], "data" : "ID", "visible": false},
	          ],
	          "aaSorting": [],
	          "lengthChange": false,
	          "info": false,
	          "autoWidth": false,
	          "searching": false
		});
	}
	init_Datatable_Gruppen();
	
	function init_Datatable_Modal_Gruppen() {
		table_select_gruppen = $('#table_select_gruppen').DataTable({
	        "language": {
	        	"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/German.json"
	        },
	        "columnDefs": [],
			"aaSorting": [],
			"lengthChange": false,
			"info": false
		});
	}
	init_Datatable_Modal_Gruppen();
	
	/* Get Value from Table Gruppen */
	$("#table_select_gruppen").find("tbody").on('click', 'tr', function () {
		selectedGruppeId = "";
		$(this).addClass('table_selection').siblings().removeClass('table_selection');
		selectedGruppeId = $(this).attr('id');
	});
	
	// Show Modal to add gruppen
	$('#choice_gruppen_dialog').on('show.bs.modal', function(event) {
		selectedGruppeId = "";
		$("#table_select_gruppen tr").removeClass('table_selection');
		table_select_gruppen.columns.adjust().draw();
	});

	// Delete gruppe
	$('#table_gruppen tbody').on( 'click', '#btn_remove_gruppe', function () {
		table_gruppen.row( $(this).parents('tr') ).remove().draw();
	});
	
	function get_essenkategorie(){
		$.ajax({
			url: '../../utilities/for_ajax_calls/get_list_essenkategorie.php',
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
	get_essenkategorie();

	// Get Data from Modal
	$('#choice_gruppen_dialog').on('hide.bs.modal', function(event) {

		var $activeElement = $(document.activeElement);
  
		if ($activeElement.is('[data-toggle], [data-dismiss]')) {
			if (event.type === 'hide') {
				if ($activeElement[0].id === 'transfer_gruppen_dialog') {

					$.ajax({
					   url: 'load_gruppe_toAdd.php',
					   method: 'POST',
					   data:{id : selectedGruppeId},
					   dataType: "json",
					   success: function(response) {
						   
						   if (response.error) {
							   alert(response.error.msg);
						   } else {
							   var tabledata = table_gruppen.rows().data();
							   
							   if (!checkIDAlreadyInTable(selectedGruppeId, tabledata)) {
								   table_gruppen.row.add({
									   	"First":		null,
									    "Link":			"<a class='btn btn-danger btn-circle custom3' href='#' id='btn_remove_gruppe'><span class='fa fa-trash-alt' title='löschen' aria-hidden='true'></span></a>",
									    "Name":			response["name"],
									    "ID":			response["id"],
								   }).draw();
							   } else {
								   alert("Der Gruppe wurde bereits hinzugefügt!");
							   }
						   }
			           },
					   error: function() {
						   alert('Fehler beim Laden des Gruppes!');
			           }
			         });
				}
			}
		}
	});

	$("#submit").click(function(event){
		event.preventDefault();
		if ($("form")[0].checkValidity() === false) {
			event.stopPropagation();
			$('form').addClass('was-validated');
		} else {
			var form_data = $("form").serialize();
			
			// New Array for IDS Gruppen
			var arrayGruppenIds = [];
			
			// Add Gruppe
			var tableData_Gruppen = table_gruppen.rows().data();
			if (tableData_Gruppen.length > 0) {
				tableData_Gruppen.each(function (value, index) {
					arrayGruppenIds.push(value['ID'])
				});
			}
			
			form_data = form_data + "&gruppen=" + arrayGruppenIds;

			$.ajax({
			   url: 'save_add_preiskategorie.php',
			   method: 'POST',
			   data: form_data,
			   success: function(data) {
				   alert('Die Preiskategorie wurde erfolgreich hinzugefügt');
				   $('form').removeClass('was-validated');
				   $("form")[0].reset();
				   
				   // Clear Table Gruppen
				   arrayGruppeIds = [];
				   selectedGruppeId = "";
				   table_gruppen.clear().draw();
	            },
			    error: function() {
				   alert('Beim Hinzufügen des Amtes ist ein Fehler aufgetreten !');
	            }
	         });
		}
		
    });
	
	function checkIDAlreadyInTable(id, tabledata) {
		var found = false;
		tabledata.each(function (value, index) {
		   if (id === value['ID']) {
			   found = true;
			   return false;
		   }
		});
		if (found) {
			return true;
		}
		return false;
	}
	
	/*
	function checkExistPreisKategorie(gastKategorie, essenKategorie) {
		$.ajax({
			url: '../../utilities/for_ajax_calls/check_exist_preis_kategorie.php',
			method: 'POST',
			data: {gastKategorie: gastKategorie, essenKategorie: essenKategorie},
			success: function(data) {
				if (data === 'false') {
					var form_data = $("form").serialize();
					addPreisKategorie(form_data);
				} else {
					alert('Diese Preiskategorie existiert bereits !');
				}
			},
			error: function() {
				alert('Bei der Überprüfung der Existenz dieser Kategorie in der Datenbanktabelle ist ein Fehler aufgetreten !');
			}
		});
	}
	*/
	
	/*
	$("#input_select0").change(function() {
		$('#input_select1')
	    .empty()
	    .append('<option selected="selected" value="">Wählen ...</option>');
		get_essenkategorie();
	});
	*/
});