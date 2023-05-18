$(document).ready( function () {
    $('#tbl_assegnazioni tfoot th').each(function () {
        var title = $(this).text();
		if (title.length!=0)
			$(this).html('<input type="text" placeholder="Search ' + title + '" />');
    });	
    var table=$('#tbl_assegnazioni').DataTable({
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
            lengthMenu: 'Visualizza _MENU_ Assegnazioni per pagina',
            zeroRecords: 'Nessuna Assegnazione trovata',
            info: 'Pagina _PAGE_ di _PAGES_',
            infoEmpty: 'Non sono presenti Assegnazioni',
            infoFiltered: '(Filtrati da _MAX_ Assegnazioni totali)',
        },

		
    });
	
} );

function dele_az(id_ref) {
	if (!confirm("Sicuri di rimuovere l'assegnazione?")) return false;
	rif = document.querySelector("#btn_dele"+id_ref);
	idus=rif.dataset.idus;
	azienda=rif.dataset.azienda;
	$("#az_dele").val(azienda)
	$("#idus_dele").val(idus)
	$("#frm_assegnazioni").submit();
}

function real_ass() {
	azienda=$("#azienda").val()
	if (azienda.trim().length==0 || azienda=="Non assegnata") {
		alert("Definire correttamente un'azienda da assegnare!")
		return false
	}
	$("#frm_new").submit();
}

function assegna(id_user) {
	$("#user_ass").val(id_user)
	$("#div_new_ass").hide(150)
	$("#div_new_ass").show(150)
}

function close_new() {
	$('#div_new_ass').hide(150);
	$('#user_ass').val('')
	$(".aziende").val('')
}
function set_a(value) {
	azienda="";
	
	if (value==1) {
		azienda=$('#list_aziende_e option:selected').text();
		old_v=$('#list_aziende_e').val()
		$("#id_fiscale").val($("#list_aziende_e").val());
	}
	if (value==2) {
		azienda=$('#list_aziende_fissi option:selected').text();		
		old_v=$('#list_aziende_fissi').val()
		$("#id_fiscale").val($("#list_aziende_fissi").val());
	}	
	if (value==3) {
		azienda=$('#list_aziende_custom option:selected').text();		
		old_v=$('#list_aziende_custom').val()
		$("#id_fiscale").val('');
	}	
	if (azienda=="Select...") {
		azienda="";
		$("#id_fiscale").val('');
	}
	
	$(".aziende").val('')
	if (value==1) $('#list_aziende_e').val(old_v)
	if (value==2) $('#list_aziende_fissi').val(old_v)
	if (value==3) $('#list_aziende_custom').val(old_v)
	azienda=azienda.trim()	
	
	$("#azienda").val(azienda)
	
	

}


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