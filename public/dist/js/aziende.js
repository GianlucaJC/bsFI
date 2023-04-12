$(document).ready( function () {

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

