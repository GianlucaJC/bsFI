$(document).ready( function () {
});

function setvalue(ref_user,periodo,id_categoria,id_attivita) {
	
	console.log(ref_user,periodo,id_categoria,id_attivita)
	html=""
	html+="<center><div class='spinner-border text-secondary' role='status'></div></center>";
	$("#bodyvalue").html(html)
	$("#div_save").empty()
	$('#modalvalue').modal('show')
	base_path = $("#url").val();
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	setTimeout(function () {
		let CSRF_TOKEN = $("#token_csrf").val();
		$.ajax({
			type: 'POST',
			url: base_path+"/getsettori",
			data: {_token: CSRF_TOKEN,ref_user:ref_user,periodo:periodo,id_categoria:id_categoria,id_attivita:id_attivita},
			success: function (settori) {
				$.ajax({
					type: 'POST',
					url: base_path+"/setvalue",
					data: {_token: CSRF_TOKEN,ref_user:ref_user,periodo:periodo,id_categoria:id_categoria,id_attivita:id_attivita},
					success: function (data) {		
						html="<div class='container-fluid'>";
							row=0
							$.each(JSON.parse(settori), function (i, item) {
								row++
								rowopen=true;rowclose=false;
								if (row/2==parseInt(row/2)) {
									rowopen=false;rowclose=true;
								}	
								name="sett"+i
								place=item.settore
								html+=obj(name,place,rowopen,rowclose);
							})	
						html+="</div>";
						$("#bodyvalue").html(html)
						$(".campi").empty();
						$.each(JSON.parse(data), function (i, item){
							ref_campo="sett"+i
							$("#"+ref_campo).val(item)
						})						
						savedata.ref_user=ref_user
						savedata.periodo=periodo
						savedata.id_categoria=id_categoria
						savedata.id_attivita=id_attivita
						
						html="<button type='button' onclick=\"savedata()\" class='btn btn-primary'>Salva dati</button>";
						$("#div_save").html(html);

					}	
				});	
			}
		});	
	},500)
	

}

function savedata() {
	var dati = $('.campi').map((_,el) => el.value).get()
	savedata.dati=dati

	console.log("ref_user",savedata.ref_user,"periodo",savedata.periodo,"id_categoria",savedata.id_categoria,"id_attivita",savedata.id_attivita)
	let CSRF_TOKEN = $("#token_csrf").val();
	
	html="<center><div class='spinner-border text-secondary' role='status'></div></center>";
	$("#div_wait").html(html)

	$.ajax({
		type: 'POST',
		url: base_path+"/savedata",
		data: {_token: CSRF_TOKEN,ref_user:savedata.ref_user,periodo:savedata.periodo,id_categoria:savedata.id_categoria,id_attivita:savedata.id_attivita,dati:savedata.dati},
		
		success: function (data) {
			$("#div_wait").empty()
			risp=JSON.parse(data)
			if (risp.header=="OK") $("#frm_dash").submit();
			else alert("Problemi occorsi durante il salvataggio!")
		},
		error: function(){
			$("#div_wait").empty();
			alert("Verificare Periodo ed Operatore!")
		}
		
	})
}
function obj(name,place,rowopen,rowclose) {	
	altro="onkeypress=\"return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))\""

	info="";
	if (rowopen==true) info="<div class='row mb-2'>";
		info+="<div class='col-md-6'>";
			info+="<div class='form-floating mb-3 mb-md-0'>";
				info+="<input class='form-control campi' id='"+name+"' name='"+name+"' "+altro+" type='text' placeholder='"+place+"'/>";
				info+="<label for='"+name+"'>"+place+"</label>";
			info+="</div>";
		info+="</div>";
	if (rowclose==true) info+="</div>";
	return info;
}

