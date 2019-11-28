$(function(){
	var hash = window.location.hash;
	hash && $('ul.nav a[href="' + hash + '"]').tab('show');
	$('#tab1').DataTable({
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
	});
});