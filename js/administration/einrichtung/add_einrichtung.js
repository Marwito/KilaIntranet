/**
 * 
 */
$(function(){
	
	var selectedAnsprechpartnerId = "";
	var table_ansprechpartner;
	var table_select_ansprechpartner;

	document.getElementById("value").innerHTML = 0;
	$('#input_text5').on('change', function(e) {
        var id = e.target.value;            
        document.getElementById("value").innerHTML = id;
    });
    $('#input_text6').change();
	
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
	            { "targets": [4], "data" : "Telefonnummer" },
	            { "targets": [5], "data" : "Mobil" },
	            { "targets": [6], "data" : "Email" },
	            { "targets": [7], "data" : "Fax" },
	            { "targets": [8], "data" : "Strasse/Hsnr" },
	            { "targets": [9], "data" : "PLZ" },
	            { "targets": [10], "data" : "Ort" },
	            { "targets": [11], "data" : "ID", "visible": false},
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
	
	/* Get Value from Table Ansprechpartner*/
	$("#table_select_ansprechpartner").find("tbody").on('click', 'tr', function () { 
		selectedAnsprechpartnerId = "";
		$(this).addClass('table_selection').siblings().removeClass('table_selection');
		selectedAnsprechpartnerId = $(this).attr('id');
	});

	// Show Modal to add Ansprechpartner
	$('#choice_ansprechpartner_dialog').on('show.bs.modal', function(event) {
		selectedAnsprechpartnerId = "";
		$("#table_select_ansprechpartner tr").removeClass('table_selection');
	});

	// Delete Ansprechpartner
	$('#table_ansprechpartner tbody').on( 'click', '#btn_remove_ansprechpartner', function () {
		table_ansprechpartner.row( $(this).parents('tr') ).remove().draw();
	});

	// Get Data from Modal
	$('#choice_ansprechpartner_dialog').on('hide.bs.modal', function(event) {

		var $activeElement = $(document.activeElement);
  
		if ($activeElement.is('[data-toggle], [data-dismiss]')) {
			if (event.type === 'hide') {
				if ($activeElement[0].id === 'transfer_ansprechpartner_dialog') {

					$.ajax({
					   url: '../amt/load_ansprechpartner_toAdd.php',
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

    $('#datetimepicker1').datetimepicker({
        locale: 'de',
        format: 'LT',
        useCurrent: false,
        stepping: 15,
        buttons: {showClose: true}
    });
	
	$('#datetimepicker1').focusout(function() {
		$(this).datetimepicker('hide');
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

			form_data = form_data + "&ansprechpartner=" + arrayAnsprechpartnerIds;

			$.ajax({
			   url: 'save_add_einrichtung.php',
			   method: 'POST',
			   data: form_data,
			   success: function(data) {
				   alert('Die Einrichtung wurde erfolgreich hinzugefügt');
				   $('form').removeClass('was-validated');
				   $("form")[0].reset();
				   document.getElementById("value").innerHTML = 0;

				   // Clear Table Ansprechpartner
				   arrayAnsprechpartnerIds = [];
				   selectedAnsprechpartnerId = "";
				   table_ansprechpartner.clear().draw();
	            },
			   error: function() {
				   alert('Beim Hinzufügen der Einrichtung ist ein Fehler aufgetreten !');
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
});