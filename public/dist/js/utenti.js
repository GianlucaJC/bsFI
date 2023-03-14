$(document).ready( function () {
    $('#tbl_list_utenti tfoot th').each(function () {
        var title = $(this).text();
		if (title.length!=0)
			$(this).html('<input type="text" placeholder="Search ' + title + '" />');
    });	
    var table=$('#tbl_list_utenti').DataTable({
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
            lengthMenu: 'Visualizza _MENU_ Utenti per pagina',
            zeroRecords: 'Nessun Utente trovato',
            info: 'Pagina _PAGE_ di _PAGES_',
            infoEmpty: 'Non sono presenti Utenti',
            infoFiltered: '(Filtrati da _MAX_ Utenti totali)',
        },

		
    });
	
} );


function edit_elem(id_elem,idu) {

	profilo=$("#profilo"+id_elem).data("descr")
	$("#profilo_user").val(profilo)
	$("#edit_elem").val(idu)
	
	$('#div_definition').show(150)
}

function dele_element(value) {
	if(!confirm('Sicuri di disabilitare l\'utente al servizio?')) 
		event.preventDefault() 
	else 
		$('#dele_contr').val(value)	
}

function abilita(value) {
	if(confirm('Sicuri di abilitare l\'utente al servizio?')) {
		$('#user_abilita').val(value)	
		$("#frm_utenti").submit();
	}
	event.preventDefault() 
	
}
function restore_element(value) {
	if(!confirm('Sicuri di ripristinare l\'elemento?')) 
		event.preventDefault() 
	else 
		$('#restore_contr').val(value)	
}