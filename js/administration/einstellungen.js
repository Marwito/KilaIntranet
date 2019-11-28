$(function(){
	var table1;
	var table2;
	var table3;
	var table4;
	var table5;
	var table6;
	var table7;
	var table8;
	var table9;
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
	
	function initialization_tab2() {
		table2 = $('#table2').DataTable({
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
	
	function initialization_tab3() {
		table3 = $('#table3').DataTable({
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
	        "autoWidth": false,
	        "searching": false
		});
	}
	
	function initialization_tab4() {
		table4 = $('#table4').DataTable({
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
	
	function initialization_tab5() {
		table5 = $('#table5').DataTable({
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
	
	function initialization_tab6() {
		table6 = $('#table6').DataTable({
	        "language": {
	        	"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/German.json"
	        },
	        "columnDefs": [
	            { "orderable": false, "targets": [0,1] },
	            { "width": '1%', "targets": [0,1] }
	          ],
	        "aaSorting": [],
	        "lengthChange": false,
	        "info": false,
	        "autoWidth": false
		});
	}
	
	function initialization_tab8() {
		table8 = $('#table8').DataTable({
	        "language": {
	        	"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/German.json"
	        },
	        "columnDefs": [
	            { "orderable": false, "targets": [0,1] },
	            { "width": '1%', "targets": [0,1] }
	          ],
	        "aaSorting": [],
	        "lengthChange": false,
	        "info": false,
	        "autoWidth": false
		});
	}
	
	function show_rechnungen_table() {
		var formData = $("form").serialize();
		$.ajax({ 
			url: '../rechnung/select_rechnungen.php',
			method: 'post', 
			data: formData, 
			success: function(result){
				if (result.indexOf('Formularvariablen') == -1) {
					$("#show").html(result);
					table7 = $('#table7').DataTable({
				        "language": {
				        	"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/German.json"
				        },
				        "columnDefs": [
				            { "orderable": false, "targets": [0,1,6] },
				            { "width": '1%', "targets": [0,1,6] }
				          ],
				          "aaSorting": [],
				          "lengthChange": false,
				          "info": false,
				          "autoWidth": false
					});
				} else {
					alert('Formularvariablen sind ungültig oder werden nicht empfangen');
				}
			},
			error: function() {
	          alert('Beim Anzeigen der Tabelle ist ein Problem aufgetreten !');
	       }
		});
	}
	
	function show_rechnungslaeufe_table() {
		$.ajax({ 
			url: '../rechnung/select_rechnungslaeufe.php',
			method: 'post', 
			success: function(result){
				$("#show1").html(result);
				table9 = $('#table9').DataTable({
			        "language": {
			        	"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/German.json"
			        },
			        "columnDefs": [
			            { "orderable": false, "targets": [0,1] },
			            { "width": '1%', "targets": [0,1] }
			          ],
			          "aaSorting": [],
			          "lengthChange": false,
			          "info": false,
			          "autoWidth": false
				});
			},
			error: function() {
	          alert('Beim Anzeigen der Tabelle ist ein Problem aufgetreten !');
	       }
		});
	}
	
	var test1 = false;
	var test2 = false;
	var test3 = false;
	var test4 = false;
	var test5 = false;
	var test6 = false;
	var test7 = false;
	var test8 = false;
	var test9 = false;
	
	var hash = window.location.hash;
	hash && $('ul.nav a[href="' + hash + '"]').tab('show');
	
	if(hash == '' || hash == '#einrichtung') {
		initialization_tab1();
		test1 = true;
	}
	
	$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
		var target = e.target.id;
		if(target =='einrichtungen-tab') {
			if(!test1) {
				initialization_tab1();
				test1 = true;
			} else {
				table1.responsive.recalc();
			}
		} else if(target =='gruppen-tab') {
			if(!test2) {
				initialization_tab2();
				test2 = true;
			} else {
				table2.responsive.recalc();
			}
		} else if(target =='preiskategorien-tab') {
			if(!test3) {
				initialization_tab3();
				test3 = true;
			} else {
				table3.responsive.recalc();
			}
		} else if(target =='amt-tab') {
			if(!test4) {
				initialization_tab4();
				test4 = true;
			} else {
				table4.responsive.recalc();
			}
		} else if(target =='kueche-tab') {
			if(!test5) {
				initialization_tab5();
				test5 = true;
			} else {
				table5.responsive.recalc();
			}
		} else if(target =='aktionsgruppen-tab') {
			if(!test6) {
				initialization_tab6();
				test6 = true;
			} else {
				table6.responsive.recalc();
			}
		} else if(target == 'rechnungen-tab') {
			if(!test7) {
				show_rechnungen_table();
				$('#datetimepicker1, #datetimepicker2').datetimepicker({
			        locale: 'de',
			        format: 'L',
			        useCurrent: false,
			        buttons: {showClose: true }
			    });
				test7 = true;
			} else {
				table7.responsive.recalc();
			}
		} else if(target == 'ansprechpartner-tab') {
			if(!test8) {
				initialization_tab8();
				test8 = true;
			} else {
				table8.responsive.recalc();
			}
		} else if(target == 'rechnungslaeufe-tab') {
			if(!test9) {
				show_rechnungslaeufe_table();
				test9 = true;
			} else {
				table9.responsive.recalc();
			}
		}
	});
	
	$('#datetimepicker1, #datetimepicker2').focusout(function() {
		$(this).datetimepicker('hide');
	});
	
	$("#datetimepicker1").on("change.datetimepicker", function (e) {
        $('#datetimepicker2').datetimepicker('minDate', e.date);
        show_rechnungen_table();
    });
	
    $("#datetimepicker2").on("change.datetimepicker", function (e) {
        $('#datetimepicker1').datetimepicker('maxDate', e.date);
        show_rechnungen_table();
    });
    
    $("#submitRechnungen").click(function(event) {
		event.preventDefault();
		if ($("form")[0].checkValidity() === false) {
			event.stopPropagation();
			$('form').addClass('was-validated');
		} else {
			if(confirm('Sind Sie sicher, alle Rechnungen dieses Monats und dieses Jahres zurückzusetzen ?')) {
				var zeitraum_von = $('#datetimepicker1').val();
				var zeitraum_bis = $('#datetimepicker2').val();
				$.ajax({
					url: '../rechnung/rechnungen_reset_by_zeitraum.php',
					method: 'POST',
					data: {zeitraum_von: zeitraum_von, zeitraum_bis: zeitraum_bis},
					dataType: 'json',
					error: function() {
						alert('Beim Zurücksetzen der Rechnungen ist ein Fehler aufgetreten !');
					},
					success: function(data) {
						if (data[1].length == 0) {
							alert("In diesem Zeitraum gibt es keine Rechnungen die zurückgesetzt werden können");
						} else {
							for (var i = 0; i < data[1].length; i++) {
								$('#table7').DataTable().row($("#"+data[1][i])).remove();
							}
							$('#table7').DataTable().draw();
							alert("Die Rechnungen wurden erfolgreich zurückgesetzt");
							$('form')[0].reset();
							show_rechnungen_table();
							show_rechnungslaeufe_table();
						}
					}
			    });
			}
		}
	});	
    
    function GetMonthNumber(monthName) {
		var monthNumber = ["Januar", "Februar", "März", "April", "Mai", "Juni",
						"Juli", "August", "September", "Oktober", "November",
						"Dezember"].indexOf(monthName) + 1;
		return monthNumber;
	}
	
	$("body").on("click",".btn-info", function(event){
		event.preventDefault();
		var monthName = $(this).closest("tr").find("td:eq(2)").text();
		var monat = GetMonthNumber(monthName);
		var jahr = $(this).closest("tr").find("td:eq(3)").text();
		monthName = $(this).closest("tr").find("td:eq(4)").text();
		var endmonat = GetMonthNumber(monthName);
		var endjahr = $(this).closest("tr").find("td:eq(5)").text();
		if(confirm('Sind Sie sicher, diesen Rechnungslauf abzuschließen ?')) {
			$.ajax({
				url: '../utilities/for_ajax_calls/rechnungslauf_abschliessen.php',
				method: 'POST',
				data: {monat: monat, jahr: jahr, endmonat: endmonat, endjahr: endjahr},
				error: function() {
					alert('Beim Abschluss des Rechnungslaufes ist ein Fehler aufgetreten !');
				},
				success: function(data) {
					show_rechnungslaeufe_table();
					show_rechnungen_table();
					alert('Der Rechnungslauf wurde erfolgreich abgeschlossen');
				}
		    });
		}
	});	
	
	$("#inputFile").fileinput({
		theme: 'fa',
		showPreview: true,
		dropZoneEnabled: false,
		showUpload : false,
		language: 'de',
		browseClass: "btn btn-success",
		removeClass: "btn btn-danger",
		uploadClass: "btn btn-primary btn-custom",
		browseLabel: "Auswählen"
	    /*previewFileIconSettings: {
	        'pdf': '<i class="fas fa-file-pdf"></i>'
	    },
	    overwriteInitial: true,
	    allowedFileExtensions: [ "pdf" ],
        type: "POST",
        uploadUrl : 'speiseplan/doc/'*/
	});

	$("#formSpeiseplan").on('submit',(function(event) {
		event.preventDefault();

		if($('#inputFile').get(0).files.length === 0) {
			alert('Bitte wählen Sie eine Datei und klicken Sie dann auf den Hochladen-Button oder geben einen Link ein!');
		} else {

			$.ajax({
				url: 'speiseplan/transfer_speiseplan.php',
				method: 'POST',
				contentType: false,
				cache: false,
				processData:false,
				data: new FormData(this),
				success: function(data) {
					if (data.error) {
						alert(data.error.msg);
						$("#btnAktSpeiseplan").prop('disabled', true);
					} else {
						alert(data);
						$("#formSpeiseplan")[0].reset();
						$("#btnAktSpeiseplan").prop('disabled', false);
					}
				},
				error: function(e) {
					alert('Beim Hochladen des Dokuments ist ein Fehler aufgetreten !');
				}
			});
		}
    }));
	
	$("body").on("click",".btn-danger", function(event){
		event.preventDefault();
		var id = $(this).parents("tr").attr("id");
		var tab = $(this).parents("table").attr("id");
		var tr = $(this).parents('tr');
	    var row = $('#'+tab).DataTable().row(tr);
		if (tab == "table7") {
			if(confirm('Möchten Sie diese Rechnung wirklich zurücksetzen ?')) {
				$.ajax({
					url: '../rechnung/rechnung_reset.php',
					method: 'POST',
					data: {id: id},
					error: function() {
						alert('Beim Zurücksetzen der Rechnung ist ein Fehler aufgetreten !');
					},
					success: function(data) {
						if (row.child.isShown()) {
							row.child( false ).remove();
							tr.removeClass('shown');
						}
						$('#table7').DataTable().row($("#"+id)).remove().draw();
						show_rechnungslaeufe_table();
						alert("Die Rechnung wurde erfolgreich zurückgesetzt");  
					}
			    });
			}
		} else if (tab == "table9") {
			var monthName = $(this).closest("tr").find("td:eq(2)").text();
			var monat = GetMonthNumber(monthName);
			var jahr = $(this).closest("tr").find("td:eq(3)").text();
			monthName = $(this).closest("tr").find("td:eq(4)").text();
			var endmonat = GetMonthNumber(monthName);
			var endjahr = $(this).closest("tr").find("td:eq(5)").text();
			if(confirm('Sind Sie sicher, diesen Rechnungslauf zurückzusetzen ?')) {
				$.ajax({
					url: '../utilities/for_ajax_calls/rechnungslauf_zuruecksetzen.php',
					method: 'POST',
					data: {monat: monat, jahr: jahr, endmonat: endmonat, endjahr: endjahr},
					error: function() {
						alert('Beim Zurücksetzen des Rechnungslaufes ist ein Fehler aufgetreten !');
					},
					success: function(data) {
						show_rechnungslaeufe_table();
						show_rechnungen_table();
						alert('Der Rechnungslauf wurde erfolgreich zurückgesetzt');
					}
			    });
			}
		} else {
			if(confirm('Möchten Sie diesen Datensatz wirklich löschen ?')) {
				if (tab == "table3") {
					var gastKategorie = $(this).closest("tr").find("td:eq(2)").text();
					var essenKategorie = $(this).closest("tr").find("td:eq(3)").text();
					$.ajax({
						url: 'preiskategorie/delete_preiskategorie.php',
						method: 'POST',
						data: {id: id, gastKategorie: gastKategorie, essenKategorie: essenKategorie},
						error: function() {
				          alert('Beim Löschen des Datensatzes ist ein Fehler aufgetreten !');
						},
						success: function(data) {
							if (row.child.isShown()) {
								row.child( false ).remove();
								tr.removeClass('shown');
							}
							$('#table3').DataTable().row($("#"+id)).remove().draw();
							alert("Der Datensatz wurde erfolgreich gelöscht");  
						}
					});
				} else {
					var value;
					var number;
					if (tab == "table1") {
						value = "einrichtung/delete_einrichtung";
					} else if (tab == "table2") {
						value = "gruppe/delete_gruppe";
					} else if (tab == "table4") {
						value = "amt/delete_amt";
					} else if (tab == "table6") {
						value = "aktionsgruppe/delete_aktionsgruppe";
					} else if (tab == "table8") {
						value = "ansprechpartner/delete_ansprechpartner";
					} else {
						value = "kueche/delete_kueche";
					}
				    $.ajax({
				       url: value + '.php',
				       method: 'POST',
				       data: {id: id},
				       error: function() {
				          alert('Beim Löschen des Datensatzes ist ein Fehler aufgetreten !');
				       },
				       success: function(data) {
				    	   if (row.child.isShown()) {
				    		   row.child( false ).remove();
				    	       tr.removeClass('shown');
				    	   }
				    	   $('#'+tab).DataTable().row($("#"+id)).remove().draw();
				           alert(data);  
				       }
				    });
				}
			}
		}
	});
});