/**
 * 
 */
$(function() {

	var selectedAnsprechpartnerId = "";
	var selectedEinrichtungId = "";
	var table_ansprechpartner;
	var table_select_ansprechpartner;
	var table_einrichtungen;
	var table_select_einrichtungen;

	function init_Datatable_Ansprechpartner() {
		table_ansprechpartner = $('#table_ansprechpartner').DataTable({
	        "language": {
	        	"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/German.json"
	        },
	        "columnDefs": [
	            { "orderable": false, "targets": [0,1] },
	            { "width": '1%', "targets": [0,1] },
	            { "targets": [0], "data" : "First" },
	            { "targets": [1], "data" : "Link" },
	            { "targets": [2], "data" : "Vorname" },
	            { "targets": [3], "data" : "Name" },
	            { "targets": [4], "data" : "Rechnung" },
	            { "targets": [5], "data" : "Telefonnummer" },
	            { "targets": [6], "data" : "Mobil" },
	            { "targets": [7], "data" : "Email" },
	            { "targets": [8], "data" : "Fax" },
	            { "targets": [9], "data" : "Strasse/Hsnr" },
	            { "targets": [10], "data" : "PLZ" },
	            { "targets": [11], "data" : "Ort" },
	            { "targets": [12], "data" : "ID", "visible": false},
	          ],
	          "aaSorting": [],
	          "lengthChange": false,
	          "info": false,
	          "autoWidth": false,
	          "searching": false
		});
	}
	init_Datatable_Ansprechpartner();
	
	function init_Datatable_Modal_Ansprechpartner() {
		table_select_ansprechpartner = $('#table_select_ansprechpartner').DataTable({
	        "language": {
	        	"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/German.json"
	        },
	        "columnDefs": [],
			"aaSorting": [],
			"lengthChange": false,
			"info": false
		});
	}
	init_Datatable_Modal_Ansprechpartner();

	function init_Datatable_Einrichtungen(){
		table_einrichtungen = $('#table_einrichtungen').DataTable({
	        "language": {
	        	"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/German.json"
	        },
	        "columnDefs": [
	            { "orderable": false, "targets": [0,1] },
	            { "width": '1%', "targets": [0,1] },
	            { "targets": [0], "data" : "First" },
	            { "targets": [1], "data" : "Link" },
	            { "targets": [2], "data" : "Name" },
	            { "targets": [3], "data" : "Strasse/Hsnr" },
	            { "targets": [4], "data" : "PLZ" },
	            { "targets": [5], "data" : "Ort" },
	            { "targets": [6], "data" : "ID", "visible": false},
	          ],
	          "aaSorting": [],
	          "lengthChange": false,
	          "info": false,
	          "autoWidth": false,
	          "searching": false
		});
	}
	init_Datatable_Einrichtungen();
	
	function init_Datatable_Modal_Einrichtungen() {
		table_select_einrichtungen = $('#table_select_einrichtungen').DataTable({
	        "language": {
	        	"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/German.json"
	        },
	        "columnDefs": [],
			"aaSorting": [],
			"lengthChange": false,
			"info": false
		});
	}
	init_Datatable_Modal_Einrichtungen();

	/* Get Value from Table Ansprechpartner*/
	$("#table_select_ansprechpartner").find("tbody").on('click', 'tr', function () {
		selectedAnsprechpartnerId = "";
		$(this).addClass('table_selection').siblings().removeClass('table_selection');
		selectedAnsprechpartnerId = $(this).attr('id');
	});
	
	/* Get Value from Table*/
	$("#table_select_einrichtungen").find("tbody").on('click', 'tr', function () {
		selectedEinrichtungId = "";
		$(this).addClass('table_selection').siblings().removeClass('table_selection');
		selectedEinrichtungId = $(this).attr('id');
	});
	
	// Show Modal to add Einrichtungen
	$('#choice_einrichtungen_dialog').on('show.bs.modal', function(event) {
		selectedEinrichtungId = "";
		$("#table_select_einrichtungen tr").removeClass('table_selection');
		table_select_einrichtungen.columns.adjust().draw();
	});
	
	// Show Modal to add Ansprechpartner
	$('#choice_ansprechpartner_dialog').on('show.bs.modal', function(event) {
		selectedAnsprechpartnerId = "";
		$("#table_select_ansprechpartner tr").removeClass('table_selection');
		table_select_ansprechpartner.columns.adjust().draw();
	});

	// Delete Ansprechpartner
	$('#table_ansprechpartner tbody').on( 'click', '#btn_remove_ansprechpartner', function () {
		table_ansprechpartner.row( $(this).parents('tr') ).remove().draw();
	});
	
	// Delete Einrichtung
	$('#table_einrichtungen tbody').on( 'click', '#btn_remove_einrichtung', function () {
		table_einrichtungen.row( $(this).parents('tr') ).remove().draw();
	});
	
	// Get Data from Modal
	$('#choice_ansprechpartner_dialog').on('hide.bs.modal', function(event) {

		var $activeElement = $(document.activeElement);
  
		if ($activeElement.is('[data-toggle], [data-dismiss]')) {
			if (event.type === 'hide') {
				if ($activeElement[0].id === 'transfer_ansprechpartner_dialog') {

					$.ajax({
					   url: 'load_ansprechpartner_toAdd.php',
					   method: 'POST',
					   data:{id : selectedAnsprechpartnerId},
					   dataType: "json",
					   success: function(response) {

						   if (response.error) {
							   alert(response.error.msg);
						   } else {
							   var tabledata = table_ansprechpartner.rows().data();
							   
							   if (!checkIDAlreadyInTable(selectedAnsprechpartnerId, tabledata)) {
								   table_ansprechpartner.row.add({
									   	"First":		null,
									    "Link":			"<a class='btn btn-danger btn-circle custom3' href='#' id='btn_remove_ansprechpartner'><span class='fa fa-trash-alt' title='löschen' aria-hidden='true'></span></a>",
									    "Vorname":		response["vorname"],
									    "Name":			response["name"],
									    "Rechnung":		response["rechnung"],
									    "Telefonnummer":response["telefonnummer"],
									    "Mobil":		response["mobil"],
									    "Email":		response["email"],
									    "Fax":			response["fax"],
									    "Strasse/Hsnr":	response["strasse"],
									    "PLZ":			response["plz"],
									    "Ort":			response["ort"],
									    "ID":			response["id"],
	
								   }).draw();
							   } else {
								   alert("Der Ansprechpartner wurde bereits hinzugefügt!");
							   }
						   }
			           },
					   error: function() {
						   alert('Fehler beim Laden des Ansprechpartners!');
			           }
			         });
				}
			}
		}
	});
	
	// Get Data from Modal
	$('#choice_einrichtungen_dialog').on('hide.bs.modal', function(event) {

		var $activeElement = $(document.activeElement);
  
		if ($activeElement.is('[data-toggle], [data-dismiss]')) {
			if (event.type === 'hide') {
				if ($activeElement[0].id === 'transfer_einrichtungen_dialog') {

					$.ajax({
					   url: 'load_einrichtung_toAdd.php',
					   method: 'POST',
					   data:{id : selectedEinrichtungId},
					   dataType: "json",
					   success: function(response) {

						   var tabledata = table_einrichtungen.rows().data();
						   
						   if (!checkIDAlreadyInTable(selectedEinrichtungId, tabledata)) {
							   table_einrichtungen.row.add({
								   	"First":		null,
								    "Link":			"<a class='btn btn-danger btn-circle custom3' href='#' id='btn_remove_einrichtung'><span class='fa fa-trash-alt' title='löschen' aria-hidden='true'></span></a>",
								    "Name":			response["name"],
								    "Strasse/Hsnr":	response["strasse"],
								    "PLZ":			response["plz"],
								    "Ort":			response["ort"],
								    "ID":			response["id"],

							   }).draw();
						   } else {
							   alert("Die Einrichtung wurde bereits hinzugefügt!");
						   }
			           },
					   error: function() {
						   alert('Fehler beim Laden der Einrichtung!');
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
			
			// New Array for IDS Ansprechaprtner
			var arrayAnsprechpartnerIds = [];
			
			// Add Ansprechpartner
			var tableData_Ansprechpartner = table_ansprechpartner.rows().data();
			if (tableData_Ansprechpartner.length > 0) {
				tableData_Ansprechpartner.each(function (value, index) {
					arrayAnsprechpartnerIds.push(value['ID'])
				});
			}
			
			// New Array for IDS Einrichtungen
			var arrayEinrichtungenIds = [];
			
			// Add Einrichtungen
			var tableData_Einrichtungen = table_einrichtungen.rows().data();
			if (tableData_Einrichtungen.length > 0) {
				tableData_Einrichtungen.each(function (value, index) {
					arrayEinrichtungenIds.push(value['ID'])
				});
			}

			form_data = form_data + "&ansprechpartner=" + arrayAnsprechpartnerIds + "&einrichtungen=" + arrayEinrichtungenIds;

			$.ajax({
			   url: 'save_add_amt.php',
			   method: 'POST',
			   data: form_data,
			   success: function(data) {

				   alert('Das Amt wurde erfolgreich hinzugefügt');
				   $('form').removeClass('was-validated');
				   $("form")[0].reset();
				   
				   // Clear Table Ansprechpartner
				   arrayAnsprechpartnerIds = [];
				   selectedAnsprechpartnerId = "";
				   table_ansprechpartner.clear().draw();
				   
				   // Clear Table Einrichtungen
				   arrayEinrichtungenIds = [];
				   selectedEinrichtungId = "";
				   table_einrichtungen.clear().draw();
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
	
	// ToDo Sascha
	// Es müsste ggf. die Tabelle mit den vorgelandenen Ansprechpartner noch geleert und dann neu erzeug twerden
});