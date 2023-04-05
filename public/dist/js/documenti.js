$(document).ready( function () {
    $('#tbl_list_doc tfoot th').each(function () {
        var title = $(this).text();
		if (title.length!=0)
			$(this).html('<input type="text" placeholder="Search ' + title + '" />');
    });	
    var table=$('#tbl_list_doc').DataTable({
		dom: 'Bfrtip',
		buttons: [
			'excel', 'pdf'
		],		
        initComplete: function () {
            // Apply the search
            this.api()
                .columns()
                .every(function () {
                    var that = this;
 
                    $('input', this.footer()).on('keyup change clear', function () {
                        if (that.search() !== this.value) {
                            that.search(this.value).draw();
                        }
                    });
                });
        },
        language: {
            lengthMenu: 'Visualizza _MENU_ Documenti per pagina',
            zeroRecords: 'Nessun Documento trovato',
            info: 'Pagina _PAGE_ di _PAGES_',
            infoEmpty: 'Non sono presenti Documenti',
            infoFiltered: '(Filtrati da _MAX_ Documenti totali)',
        },

		
    });
	
} );



function dele_element(value) {
	if(!confirm('Sicuri di disabilitare l\'utente al servizio?')) 
		event.preventDefault() 
	else 
		$('#dele_contr').val(value)	
}


function restore_element(value) {
	if(!confirm('Sicuri di ripristinare l\'elemento?')) 
		event.preventDefault() 
	else 
		$('#restore_contr').val(value)	
}