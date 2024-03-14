$(document).ready( function () {
 var table = $('#tbl_list_doc').DataTable({
		order: [ 0, 'desc' ],
		dom: 'Bfrtip',
		buttons: [
			'excel', 'pdf'
		],			 
        language: {
            lengthMenu: 'Visualizza _MENU_ Documenti per pagina',
            zeroRecords: 'Nessun Documento trovato',
            info: 'Pagina _PAGE_ di _PAGES_',
            infoEmpty: 'Non sono presenti Documenti',
            infoFiltered: '(Filtrati da _MAX_ Documenti totali)',
        },
	});

    $("#tbl_list_doc tfoot th").each( function ( i ) {
		
		if ($(this).text() !== '') {
	        var isStatusColumn = (($(this).text() == 'Status') ? true : false);
			var select = $('<select style="max-width:400px"><option value=""></option></select>')
	            .appendTo( $(this).empty() )
	            .on( 'change', function () {
	                var val = $(this).val();
					
	                table.column( i )
	                    .search( val ? '^'+$(this).val()+'$' : val, true, false )
	                    .draw();
	            } );
	 		
			// Get the Status values a specific way since the status is a anchor/image
			if (isStatusColumn) {
				var statusItems = [];
				
                /* ### IS THERE A BETTER/SIMPLER WAY TO GET A UNIQUE ARRAY OF <TD> data-filter ATTRIBUTES? ### */
				table.column( i ).nodes().to$().each( function(d, j){
					var thisStatus = $(j).attr("data-filter");
					if($.inArray(thisStatus, statusItems) === -1) statusItems.push(thisStatus);
				} );
				
				statusItems.sort();
								
				$.each( statusItems, function(i, item){
				    select.append( '<option value="'+item+'">'+item+'</option>' );
				});

			}
            // All other non-Status columns (like the example)
			else {
				table.column( i ).data().unique().sort().each( function ( d, j ) {  
					select.append( '<option value="'+d+'">'+d+'</option>' );
		        } );	
			}
	        
		}
    } );
  
	
} );


function newdoc() {
	html=""
	html+="<center><div class='spinner-border text-secondary' role='status'></div></center>";
	$("#title_doc").html("Nuovo documento utile")
	$("#bodyvalue").html(html)
	$("#div_save").empty()
	$('#modalvalue').modal('show')	

	
	fetch('class_allegati.php', {
		method: 'post',
		//cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached		
		headers: {
		  "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
		},
		body: 'operazione=refresh_tipo'
	})
	.then(response => {
		if (response.ok) {
		   return response.text();
		}
		
	})
	.then(resp=>{
		//$("#div_sezione"+sezione).html(resp);
		
		$("#bodyvalue").html(resp);
		
		//function set_class_allegati() in demo-config.js
		set_class_allegati.from="allegati_utili"
		set_class_allegati(); 

		html="<button id='btn_save' disabled type='button' class='btn btn-primary' onclick='saveinfodoc()'>Salva</button>";
		//saveinfodoc() in demo-config.js

		
		$("#div_save").html(html);		
	})
	.catch(status, err => {
		
		return console.log(status, err);
	})	
		
	
}


function close_doc() {
	//impostato da dash.js o demo-config.js
	if( typeof close_doc.tipo == 'undefined' ) return false
	if (close_doc.tipo=="refresh") $("#frm_documenti").submit();	
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