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
			wx="200px";

			if (i==2) wx="400px";
			var select = $('<select style="max-width:'+wx+'"><option value=""></option></select>')
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
					dx=d
					if (i==2){
						var regex = /(&nbsp;|<([^>]+)>)/ig
						,   body = d
						,   dx = body.replace(regex, "");
												
					}
					select.append( '<option value="'+dx+'">'+dx+'</option>' );
		        } );	
			}

	        
		}
    } );
  
	
} );





function dele_element(value) {
	if(!confirm('Sicuri di eliminare il documento?')) 
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