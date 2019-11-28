/**
 * 
 */
$(function(){

	var selectedKindId;
	var selectedAnsprechpartnerId;
	var table_but_kind;
	var table_but_ansprechpartner;
	
	function init_Datatable_Assistent_Kind() {
		table_but_kind = $('#table_but_kind').DataTable({
	        "language": {
	        	"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/German.json"
	        },
	        "columnDefs": [
	            { "orderable": false, "targets": [0] },
	            { "width": '1%', "targets": [0] },
	            { "targets": [0], "data" : "First" },
	            { "targets": [1], "data" : "Vorname" },
	            { "targets": [2], "data" : "Name" },
	            { "targets": [3], "data" : "ID", "visible": false},
	          ],
	          "aaSorting": [],
	          "lengthChange": false,
	          "info": false,
	          "autoWidth": false
		});
		
		selectedKindId = $("#kindID").val();
	}
	init_Datatable_Assistent_Kind();

	function init_Datatable_Assistent_Ansprechpartner() {
		table_but_ansprechpartner = $('#table_but_ansprechpartner').DataTable({
	        "language": {
	        	"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/German.json"
	        },
	        "columnDefs": [
	            { "orderable": false, "targets": [0] },
	            { "width": '1%', "targets": [0] },
	            { "targets": [0], "data" : "First" },
	            { "targets": [1], "data" : "Vorname" },
	            { "targets": [2], "data" : "Name" },
	            { "targets": [3], "data" : "ID", "visible": false},
	          ],
	          "aaSorting": [],
	          "lengthChange": false,
	          "info": false,
	          "autoWidth": false
		});
		
		selectedAnsprechpartnerId = $("#ansprechpartnerID").val();
	}
	init_Datatable_Assistent_Ansprechpartner();
	
	$('#table_but_kind tbody').on( 'click', 'tr', function () {
		$("#error_kindId").hide();
    });
	
	$('#table_but_ansprechpartner tbody').on( 'click', 'tr', function () {
		$("#error_ansprechpartnerId").hide();
    });
	
	table_but_kind.rows().every(function(rowIdx, tableLoop, rowLoop){
	    var rowData = this.data();
        if(rowData['ID'] === selectedKindId){
            //console.log("on row "+rowIdx+" found :"+rowData.email+ " address");
            $(this.node()).addClass('table_selection');
            return false;
        }      
	});
	
	table_but_ansprechpartner.rows().every(function(rowIdx, tableLoop, rowLoop){
	    var rowData = this.data();
        if(rowData['ID'] === selectedAnsprechpartnerId){
            //console.log("on row "+rowIdx+" found :"+rowData.email+ " address");
            $(this.node()).addClass('table_selection');
            return false;
        }      
	});

	/* Selektiertes Kind */
	$("#table_but_kind").find("tbody").on('click', 'tr', function () { 
		selectedKindId = "";
		$(this).addClass('table_selection').siblings().removeClass('table_selection');
		selectedKindId = $(this).attr('id');
	});
	
	/* Selektierter Ansprechpartner */
	$("#table_but_ansprechpartner").find("tbody").on('click', 'tr', function () {  
		selectedAnsprechpartnerId = "";
		$(this).addClass('table_selection').siblings().removeClass('table_selection');
		selectedAnsprechpartnerId = $(this).attr('id');
	});
	
	// initialization
	/*var currentSelectedStudio;
	var terminSelector = $('#termin');
	var studioSelector = $('#studio');
	var selectedTaskValue;
	
	var start = false;*/
	var next_fs;
	var current_fs;
	var wizardForm = $("form");
	$("#submit").hide();
	$("#error_kindId").hide();
	$("#error_ansprechpartnerId").hide();
	
	// Initial
	if (next_fs === "undefined" &&
			current_fs === "undefined") {
	
		$("#alert_view").remove();
		$("#submit").hide();
	
		current_fs = $('#but_step-1');
		current_fs.show();
		current_fs2 = $('#but_step-2');
		current_fs2.hide();
		current_fs3 = $('#but_step-3');
		current_fs3.hide();
		current_fs4 = $('#but_step-4');
		current_fs4.hide();
		current_fs5 = $('#but_step-5');
		current_fs5.hide();
		current_fs6 = $('#but_step-6');
		current_fs6.hide();
		current_fs7 = $('#but_step-7');
		current_fs7.hide();
		current_fs7 = $('#but_step-8');
		current_fs7.hide();
		
		$("#li-but_step-2").removeClass("active");
		$("#li-but_step-3").removeClass("active");
		$("#li-but_step-4").removeClass("active");
		$("#li-but_step-5").removeClass("active");
		$("#li-but_step-6").removeClass("active");
		$("#li-but_step-7").removeClass("active");
		$("#li-but_step-8").removeClass("active");
	}  
	
	$(".next").click(function() {
		
		wizardForm.validate({
		  errorElement: 'span',
		  errorClass: 'help-block',
		  highlight: function(element, errorClass, validClass) {
		    $(element).closest('.form-group').addClass("has-error");
		  },
		  unhighlight: function(element, errorClass, validClass) {
		    $(element).closest('.form-group').removeClass("has-error");
		  },
		  rules: {
			aktenzeichen: {
				required: true,
			},
			datetimepicker_bescheid_von: {
				required: true,
			},
			datetimepicker_bescheid_bis: {
				required: true,
			},
			anteilsart: {
				required: true,
			},
			anteilsbetrag: {
				required: true,
				currency: true,
			},
			debitorennummer: {
				required: true,
			},
		  },
		  messages: {
			aktenzeichen: {
				required: "Bitte geben Sie ein Aktenzeichen ein!",
			},
			datetimepicker_bescheid_von: {
				required: "Bitte geben Sie ein Datum von ein!",
			},
			datetimepicker_bescheid_bis: {
				required: "Bitte geben Sie ein Datum bis ein!",
			},
			anteilsart: {
				required: "Bitte wählen Sie eine Anteilsart aus!",
			},
			anteilsbetrag: {
				required: "Bitte geben Sie einen Anteilsbetrag ein!"
			},
			debitorennummer: {
				required: "Bitte geben Sie eine Debitorennummer ein!",
			},
		  }
		});		

		if (wizardForm.valid() === true) {

			if ($('#but_step-1').is(":visible")) {
				
				// Custom Validation
				if (selectedKindId === undefined || selectedKindId === "") {
					$("#error_kindId").show();
				} else {
					current_fs = $('#but_step-1');
					next_fs = $('#but_step-2');
					$("#li-but_step-2").addClass("active");
					
					// Next step
					next_fs.show(); 
			        current_fs.hide();
				}
			} else if($('#but_step-2').is(":visible")) {
				current_fs = $('#but_step-2');
				next_fs = $('#but_step-3');
				$("#li-but_step-3").addClass("active");
				
				// Next step
				next_fs.show(); 
		        current_fs.hide();
			} else if($('#but_step-3').is(":visible")) {
				
				// Custom Validation
				if (selectedAnsprechpartnerId === undefined || selectedAnsprechpartnerId === "") {
					$("#error_ansprechpartnerId").show();
				} else {
				
					current_fs = $('#but_step-3');
					next_fs = $('#but_step-4');
					$("#li-but_step-4").addClass("active");
					
					// Next step
					next_fs.show(); 
			        current_fs.hide();
				}
			} else if($('#but_step-4').is(":visible")) {
				current_fs = $('#but_step-4');
				next_fs = $('#but_step-5');
				$("#li-but_step-5").addClass("active");
				
				// Next step
				next_fs.show(); 
		        current_fs.hide();
			} else if($('#but_step-5').is(":visible")) {
				current_fs = $('#but_step-5');
				next_fs = $('#but_step-6');
				$("#li-but_step-6").addClass("active");
				
				// Next step
				next_fs.show(); 
		        current_fs.hide();
			} else if($('#but_step-6').is(":visible")) {
				current_fs = $('#but_step-6');
				next_fs = $('#but_step-7');
				$("#li-but_step-7").addClass("active");

				// Next step
				next_fs.show(); 
		        current_fs.hide();
			} else if($('#but_step-7').is(":visible")) {
				current_fs = $('#but_step-7');
				next_fs = $('#but_step-8');
				$("#li-but_step-8").addClass("active");
				
				// Speicher-Div anzeigen
				$("#submit").show();

				// Next step
				next_fs.show(); 
		        current_fs.hide();
			} 
		}
	});
    
    $('.prev').click(function(){

    	if($('#but_step-8').is(":visible")) {
            current_fs = $('#but_step-8');
            next_fs = $('#but_step-7');
            $("#li-but_step-8").removeClass("active");

			// Speicher-Div versteken
			$("#submit").hide();

        } else if($('#but_step-7').is(":visible")) {
            current_fs = $('#but_step-7');
            next_fs = $('#but_step-6');
            $("#li-but_step-7").removeClass("active");
        } else if($('#but_step-6').is(":visible")) {
            current_fs = $('#but_step-6');
            next_fs = $('#but_step-5');
            $("#li-but_step-6").removeClass("active");
        } else if($('#but_step-5').is(":visible")) {
            current_fs = $('#but_step-5');
            next_fs = $('#but_step-4');
            $("#li-but_step-5").removeClass("active");
        } else if($('#but_step-4').is(":visible")) {
        	current_fs = $('#but_step-4');
        	next_fs = $('#but_step-3');
        	$("#li-but_step-4").removeClass("active");
        } else if ($('#but_step-3').is(":visible")) {
			current_fs = $('#but_step-3');
			next_fs = $('#but_step-2');
			$("#li-but_step-3").removeClass("active");
        } else if ($('#but_step-2').is(":visible")) {
			current_fs = $('#but_step-2');
			next_fs = $('#but_step-1');
			$("#li-but_step-2").removeClass("active");
        }

        next_fs.show(); 
        current_fs.hide();
    });

    $.validator.addMethod("currency", function (value, element) {
  	  return this.optional(element) || /(\d{1,3}(\,\d{3})*|(\d+))(\.\d{2})?$/.test(value);
  	}, "Bitte geben Sie einen korrekten Betrag ein!");

    $('#datetimepicker_bescheid_von').datetimepicker({
        locale: 'de',
        format: 'L',
        useCurrent: false,
        buttons: {showClose: true }
    });
	
	$('#datetimepicker_bescheid_von').focusout(function() {
		$(this).datetimepicker('hide');
	});
	
    $('#datetimepicker_bescheid_bis').datetimepicker({
        locale: 'de',
        format: 'L',
        useCurrent: false,
        buttons: {showClose: true }
    });
	
	$('#datetimepicker_bescheid_bis').focusout(function() {
		$(this).datetimepicker('hide');
	});
	
	$("#submit").click(function(event) {
		event.preventDefault();
		
		var form_data = $("form").serialize();
		form_data = form_data + "&kind=" + selectedKindId + "&ansprechpartner=" + selectedAnsprechpartnerId;

		$.ajax({
		   url: 'save_edit_but.php',
		   method: 'POST',
		   data: form_data,
		   success: function(data) {
			   
			   if (data.error) {
				   alert(data.error.msg);
			   } else {
				   alert(data);
	
				   //$('wizardForm').removeClass('was-validated');
				   $("form")[0].reset();
				   
				   window.location.href="but.php";
				   
				   // Clear Table Ansprechpartner
				   selectedKindId = "";
				   selectedAnsprechpartnerId = "";
			   }
            },
			error: function() {
				alert('Beim Hinzufügen des BUTs ist ein Fehler aufgetreten !');
	        }
        });
    });
});