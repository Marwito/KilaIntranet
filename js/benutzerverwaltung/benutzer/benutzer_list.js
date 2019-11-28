$(function(){
	var hash = window.location.hash;
	hash && $('ul.nav a[href="' + hash + '"]').tab('show');
	if (user == 1) {
		var table = $('#table1').DataTable({
	        "language": {
	        	"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/German.json"
	        },
	        "columnDefs": [
	            { "orderable": false, "targets": [0,1,7,8,9,10,11] },
	            { "width": '1%', "targets": [0,1,7,8,9,10,11] }
	          ],
	        "aaSorting": [],
	        "lengthChange": false,
	        "paging": false,
	        "info": false
		});
	} else {
		var table = $('#table1').DataTable({
	        "language": {
	        	"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/German.json"
	        },
	        "columnDefs": [
	            { "orderable": false, "targets": [0,1,6,7,8,9,10] },
	            { "width": '1%', "targets": [0,1,6,7,8,9,10] }
	          ],
	        "aaSorting": [],
	        "lengthChange": false,
	        "paging": false,
	        "info": false
		});
	}

	$("body").on('click', "input[type='checkbox']", function (event) {
		var id = $(this).parents("tr").attr("id");
		if (id == undefined) {
			id = $(this).parents("tr").prev().attr("id");
		}
		var message1;
		var url;
		var message2;
		if(this.checked == true){
			message1 = 'Bei der Aktivierung dieses Benutzers ist ein Fehler aufgetreten !';
			url = 'activate_benutzer.php';
			message2 = 'Der Benutzer wurde erfolgreich aktiviert';
		} else {
			message1 = 'Bei der Deaktivierung dieses Benutzers ist ein Fehler aufgetreten !';
			url = 'deactivate_benutzer.php';
			message2 = 'Der Benutzer wurde erfolgreich deaktiviert';
		}
		 
		$.ajax({
			url: url,
			method: 'POST',
			data: {id: id},
			error: function() {
				alert(message1);
			},
			success: function(data) {
				alert(message2);  
			}
		});
	});
	/*
	$("#schicken").click(function(event) {
		event.preventDefault();
		var benutzername = $(this).closest("tr").find("td:eq(2)").text();
		var email = $(this).closest("tr").find("td:eq(11)").text();
	    $.ajax({
	       url: '../zugang/send_zugangsdaten.php',
	       method: 'POST',
	       data: {benutzername: benutzername, email: email},
	       error: function() {
	          alert('Beim Senden der Zugangsdaten ist ein Fehler aufgetreten !');
	       },
	       success: function(data) {
	    	   if (data == 'OK') {
	    		   alert("Die Zugangsdaten wurden erfolgreich gesendet");
			   } else if (data == 'no success')  {
				   alert('Die E-Mail konnte nicht an den Empfänger gesendet werden');
			   } else {
				   alert('Die zum Senden der E-Mail erforderlichen Variablen wurden nicht empfangen');
			   }
	       }
	    });
	});
	*/
    $(".btn-danger").click(function(event){
		event.preventDefault();
		if(confirm('Möchten Sie diesen Datensatz wirklich löschen ?')) {
			var id = $(this).parents("tr").attr("id");
			var tr = $(this).parents('tr');
		    var row = $('#table1').DataTable().row(tr);
		    $.ajax({
		       url: './delete_benutzer.php',
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
		    	   $('#table1').DataTable().row($("#"+id)).remove().draw();
		           alert("Der Datensatz wurde erfolgreich gelöscht");
		       }
		    });
		}
	});
});