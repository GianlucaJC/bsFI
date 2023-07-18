$(document).ready( function () {
	$('.select2').select2()

} );

function dele_az() {
	if (!confirm("Sicuri di cancellare l'azienda?")) return false;
	azienda=$('#dele_azienda option:selected').text();
	$("#az_dele").val(azienda)
	$("#frm_aziende_dele").submit();
}

function view_dele(value) {
	if (value.trim().length>0) 
		$("#btn_dele_az").show(150)
	else
		$("#btn_dele_az").hide(150)
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


function add_doc() {
	azienda="";id_azienda="";
	if (add_doc.from==1) {
		id_azienda=$( "#list_aziende_e" ).val()
		azienda=$( "#list_aziende_e option:selected" ).text()
	}
	if (add_doc.from==2) {
		azienda=$( "#list_aziende_fissi option:selected" ).text()
	}
	if (add_doc.from==3) {
		azienda=$( "#dele_azienda option:selected" ).text()
	}
	if (azienda.length>0) {
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
			
			resp+="<hr><button id='btn_save' disabled type='button' class='btn btn-primary' onclick='saveinfoazi()'>Salva</button>";
			//saveinfocant() in demo-config.js
			$("#div_save").html(resp);		
			//function set_class_allegati() in demo-config.js
			set_class_allegati.from="allegati_aziende"

			set_class_allegati.id_azienda=id_azienda
			set_class_allegati.azienda=azienda
			set_class_allegati(); 			
		})
		.catch(status, err => {
			
			return console.log(status, err);
		})			
	}
}

function check_add(from) {
	$("#div_save").empty()
	add_doc.from=0
	entr=false
	azienda="";az=""
	$( "#btn_associa" ).prop( "disabled", true );
	if (from==1) 
		az=$("#list_aziende_e").val()
	if (from==2) 
		az=$("#list_aziende_fissi").val()
	if (from==3) 
		az=$("#dele_azienda").val()
		
	if (az.length>0) {
		entr=true
		azienda="<b>Associa un documento all'azienda"
		
		if (from==1) 
			azienda+=$( "#list_aziende_e option:selected" ).text()+"</b>";
		if (from==2) 
			azienda+=$( "#list_aziende_fissi option:selected" ).text()+"</b>";
		if (from==3) 
			azienda+=$( "#dele_azienda option:selected" ).text()+"</b>";
	}	
	if (entr==true) {
		add_doc.from=from
		$( "#btn_associa" ).prop( "disabled", false );
		$("#btn_associa").html(azienda)
	} else $("#btn_associa").html("Associa un documento all'azienda")
	
}

