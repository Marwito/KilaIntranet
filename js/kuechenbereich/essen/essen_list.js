$(function(){
	function initialization_tab(number) {
		$('#table'+number).DataTable({
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
	var test2 = false;
	var test3 = false;
	
	initialization_tab(1);
	var hash = window.location.hash;
	hash && $('ul.nav a[href="' + hash + '"]').tab('show');
	$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
		var target = e.target.id;
		if (target =='zutaten-tab' && test3 == false) {
			initialization_tab(3);
			test3 = true;
		}
		if (target =='rezepte-tab' && test2 == false) {
			initialization_tab(2);
			test2 = true;
		}
	});

	$(".btn-danger").click(function(event){
		event.preventDefault();
		if (confirm('Möchten Sie diesen Datensatz wirklich löschen ?')) {
			var id = $(this).parents("tr").attr("id");
			var tab = $(this).parents("table").attr("id");
			var tr = $(this).parents('tr');
		    var row = $('#'+tab).DataTable().row(tr);
			var value;
			if (tab == "table1") {
				value = "speiseplan/delete_speiseplan";
			}
			else if (tab == "table2") {
				value = "rezept/delete_rezept";
			} else {
				value = "zutat/delete_zutat";
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
		           alert("Der Datensatz wurde erfolgreich gelöscht");  
		       }
		    });
		}
	});
});