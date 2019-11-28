/**
 * 
 */
$(function(){

	function initDataTable() {
		var table_but_kind = $('#table_but').DataTable({
	        "language": {
	        	"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/German.json"
	        },
	        "columnDefs": [
	            { "orderable": false, "targets": [0, 1] },
	            { "width": '1%', "targets": [0, 1] },
	            { "targets": [0], "data" : "First" },
	            { "targets": [1], "data" : "Link" },
	            { "targets": [2], "data" : "Debitorennummer" },
	            { "targets": [3], "data" : "Vorname" },
	            { "targets": [4], "data" : "Name" },
	            { "targets": [5], "data" : "ID", "visible": false},
	          ],
	          "aaSorting": [],
	          "lengthChange": false,
	          "info": false,
	          "autoWidth": false
		});
	}
	initDataTable();
	
	$("body").on("click",".btn-danger", function(event){
		event.preventDefault();
		if (confirm('Möchten Sie diesen BUT wirklich löschen ?')) {
			var id = $(this).parents("tr").attr("id");
			var tr = $(this).parents('tr');
		    var row = $('#table_but').DataTable().row(tr);
		    $.ajax({
		       url: 'delete_but.php',
		       method: 'POST',
		       data: {id: id},
		       error: function() {
		          alert('Beim Löschen des BUTs ist ein Fehler aufgetreten !');
		       },
		       success: function(data) {
		    	   
		    	   if (data.error) {
		    		   alert(data.error.msg);
		    	   } else {
		    		   alert(data);
		    		   if (row.child.isShown()) {
			    		   row.child( false ).remove();
			    	       tr.removeClass('shown');
			    	   }
			    	   $('#table_but').DataTable().row($("#"+id)).remove().draw(); 
		    	   }
		       }
		    });
		}
	});
});