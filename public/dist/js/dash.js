$(document).ready( function () {

});

function delerow(id_row) {
	if (!confirm("Sicuri di cancellare il documento?")) return false;
	
	base_path = $("#url").val();
	let CSRF_TOKEN = $("#token_csrf").val();
	html=""
	html+="<center><div class='spinner-border spinner-border-sm text-secondary' role='status'></div></center>";
	
	$("#dele_doc"+id_row).html(html)
	setTimeout(function(){
		
		fetch(base_path+'/delerow', {
			method: 'post',
			//cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached		
			headers: {
			  "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
			},
			body: '_token='+ CSRF_TOKEN+'&id_row='+id_row
		})
		.then(response => {
			if (response.ok) {
			   return response.json();
			}
		})
		.then(resp=>{
			if (resp.status=="KO") {
				alert("Problemi occorsi durante il salvataggio.\n\nDettagli:\n"+resp.message);
				return false;
			}
			$("#tr_doc"+id_row).remove();
			close_doc.tipo="refresh";
		})
		.catch(status, err => {
			return console.log(status, err);
		})	
	},500)		
	
}


function delerowazi(id_doc) {
	if (!confirm("Sicuri di cancellare il documento?")) return false;
	
	base_path = $("#url").val();
	let CSRF_TOKEN = $("#token_csrf").val();
	html=""
	html+="<center><div class='spinner-border spinner-border-sm text-secondary' role='status'></div></center>";
	
	$("#dele_doc_cant"+id_doc).html(html)
	setTimeout(function(){
		
		fetch(base_path+'/delerowazi', {
			method: 'post',
			//cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached		
			headers: {
			  "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
			},
			body: '_token='+ CSRF_TOKEN+'&id_doc='+id_doc
		})
		.then(response => {
			if (response.ok) {
			   return response.json();
			}
		})
		.then(resp=>{
			if (resp.status=="KO") {
				alert("Problemi occorsi durante la cancellazione del file.\n\nDettagli:\n"+resp.message);
				$("#dele_doc_azi"+id_doc).html('')
				return false;
			}
			$("#tr_doc_azi"+id_doc).remove();
			close_doc.tipo="refresh";			
		})
		.catch(status, err => {
			return console.log(status, err);
		})	
	},500)		
	
}

function delerowcant(id_doc) {
	if (!confirm("Sicuri di cancellare il documento?")) return false;
	
	base_path = $("#url").val();
	let CSRF_TOKEN = $("#token_csrf").val();
	html=""
	html+="<center><div class='spinner-border spinner-border-sm text-secondary' role='status'></div></center>";
	
	$("#dele_doc_cant"+id_doc).html(html)
	setTimeout(function(){
		
		fetch(base_path+'/delerowcant', {
			method: 'post',
			//cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached		
			headers: {
			  "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
			},
			body: '_token='+ CSRF_TOKEN+'&id_doc='+id_doc
		})
		.then(response => {
			if (response.ok) {
			   return response.json();
			}
		})
		.then(resp=>{
			if (resp.status=="KO") {
				alert("Problemi occorsi durante la cancellazione del file.\n\nDettagli:\n"+resp.message);
				$("#dele_doc_cant"+id_doc).html('')
				return false;
			}
			$("#tr_doc_cant"+id_doc).remove();
			close_doc.tipo="refresh";			
		})
		.catch(status, err => {
			return console.log(status, err);
		})	
	},500)		
	
}


function view_row(ref_user,periodo,id_categoria,id_attivita,id_settore,azienda) {
	if (azienda.length!=0) {
		alert("Attenzione, quando si seleziona un'azienda nonostante vengano filtrati tutti i documenti relativi, cliccando sul numero saranno comunque mostrati tutti i documenti a prescindere dall'azienda scelta");
	}
	html=""
	html+="<center><div class='spinner-border text-secondary' role='status'></div></center>";
	
	$("#title_doc").html("Elenco documenti inviati")
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
					url: base_path+"/inforow",
					data: {_token: CSRF_TOKEN,ref_user:ref_user,periodo:periodo,id_categoria:id_categoria,id_attivita:id_attivita,id_settore:id_settore},
					success: function (data) {		
						var sett = $.parseJSON( settori );
						if (sett[id_settore]) console.log(sett[id_settore])
						console.table(data)
						
						html="<div class='container-fluid' id='div_main_value'>";
							html+="<table id='tb_inforow' class='table table-striped table-valign-middle' style='padding:0.15rem'>";
								html+="<thead>";
									html+="<tr>";
										html+="<th>Elimina</th>";
										html+="<th>Azienda</th>";
										html+="<th>Documento</th>";
										html+="<th>Inviato il</th>";
									html+="</tr>";
								html+="</thead>";
								html+="<tbody>";
								entr=0
								user_log=0
								$.each(JSON.parse(data), function (i, item) {
									if (entr==0) {
										user_log=item.user_log
										entr=1
									}
									html+="<tr id='tr_doc"+item.id+"'>";
										html+="<td>";
										if (user_log==item.id_funzionario) {
											html+="<span id='dele_doc"+item.id+"'></span>";
												html+="<button type='button' class='btn btn-warning btn-sm' onclick='delerow("+item.id+")'>Elimina</button>";
										}
										html+="</td>";
										html+="<td>";
											if (item.azienda)
												html+=item.azienda
										html+="</td>";
										html+="<td>"
										html+="<a href='"+item.url_completo+"' target='_blank'>";
											if (item.file_user.length>0)
												html+=item.file_user
											else
												html+=item.filename
										html+="</a>";
										html+="<td>";
											html+=item.periodo_data
										html+="</td>";
									html+="</tr>";
								})
								html+="</tbody>";
							html+="</table>";
						html+="</div>";
						
						$("#bodyvalue").html(html)
						
						
						//per aggiungere bottoni sul footer del modal
						$("#div_save").empty();

					}	
				});	
			}
		});	
	},500)	
}

function setvalue(ref_user,periodo,id_categoria,id_attivita) {
	console.log(ref_user,periodo,id_categoria,id_attivita)

	html=""
	html+="<center><div class='spinner-border text-secondary' role='status'></div></center>";
	$("#title_doc").html("Inserimento dati")
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
			url: base_path+"/get_settori_aziende",
			data: {_token: CSRF_TOKEN,ref_user:ref_user,periodo:periodo,id_categoria:id_categoria,id_attivita:id_attivita},
			success: function (risposta) {
				$.ajax({
					type: 'POST',
					url: base_path+"/setvalue",
					data: {_token: CSRF_TOKEN,ref_user:ref_user,periodo:periodo,id_categoria:id_categoria,id_attivita:id_attivita},
					success: function (data) {
						
						console.log(risposta)
						risp=$.parseJSON( risposta)
						
						view_form.settori=risp.settori
						view_form.aziende_e=risp.aziende_e
						view_form.aziende_fissi=risp.aziende_fissi
						view_form.aziende_custom=risp.aziende_custom
						
						html=view_form()
						
						$("#bodyvalue").html(html)
						
						$('.aziende').select2({
							dropdownParent: $('#modalvalue')
						});	
						
						
						/*
						$(".campi").empty();
						$.each(JSON.parse(data), function (i, item){
							ref_campo="sett"+i
							$("#"+ref_campo).val(item)
						})
						*/
						
						step2.ref_user=ref_user
						step2.periodo=periodo
						step2.id_categoria=id_categoria
						step2.id_attivita=id_attivita
					
						html="<button id='btn_save' disabled type='button' class='btn btn-primary' onclick='saveinfo()'>Salva</button>";
						//saveinfo() in demo-config.js

						
						$("#div_save").html(html);

					}	
				});	
			}
		});	
	},500)
	
}


function docinazienda(id_azienda,id_a) {
	html=""
	html+="<center><div class='spinner-border text-secondary' role='status'></div></center>";
	$("#title_doc").html("Elenco documenti relativi all'azienda")
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
			url: base_path+"/docinazienda",
			data: {_token: CSRF_TOKEN,id_azienda:id_azienda,id_a:id_a},
			success: function (risposta) {

				html="<div class='container-fluid' id='div_main_value'>";
					html+="<table id='tb_inforow' class='table table-striped table-valign-middle' style='padding:0.15rem'>";
						html+="<thead>";
							html+="<tr>";
								html+="<th>Elimina</th>";
								html+="<th>Inserito da</th>";
								html+="<th>Documento</th>";
								html+="<th style='text-align:center'>Creato il</th>";
							html+="</tr>";
						html+="</thead>";
						html+="<tbody>";
						ent=0;user_ref=0;
						$.each(JSON.parse(risposta), function (i, item) {
							if (ent==0) user_ref=item.user_log
							ent=1
							nomef=item.file_user
							url_completo=item.url_completo
							
							html+="<tr id='tr_doc_azi"+item.id+"'>";
								html+="<td> <span id='dele_doc_azi"+item.id+"'></span>";
								if (user_ref==item.id_funzionario) {
									html+="<button type='button' class='btn btn-warning btn-sm' onclick='delerowazi("+item.id+")'>Elimina</button>";
								}
								html+="</td>";
								
								html+="<td>";
									html+=item.name
								html+="</td>";
								html+="<td>"
								html+="<a href='"+url_completo+"' target='_blank'>";
										html+=nomef
								html+="</a>";
								html+="<td style='text-align:center'>"
										html+=item.created_at
								html+="</td>";

							html+="</tr>";
						})
						html+="</tbody>";
					html+="</table>";
				html+="</div>";
				
				$("#bodyvalue").html(html)



			}			
		});	
	},500)
	
}

function docincantiere(id_cantiere) {
	html=""
	html+="<center><div class='spinner-border text-secondary' role='status'></div></center>";
	$("#title_doc").html("Elenco documenti relativi al cantiere")
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
			url: base_path+"/docincantiere",
			data: {_token: CSRF_TOKEN,id_cantiere:id_cantiere},
			success: function (risposta) {

				html="<div class='container-fluid' id='div_main_value'>";
					html+="<table id='tb_inforow' class='table table-striped table-valign-middle' style='padding:0.15rem'>";
						html+="<thead>";
							html+="<tr>";
								html+="<th>Elimina</th>";
								html+="<th>Inserito da</th>";
								html+="<th>Documento</th>";
								html+="<th style='text-align:center'>Creato il</th>";
							html+="</tr>";
						html+="</thead>";
						html+="<tbody>";
						ent=0;user_ref=0;
						$.each(JSON.parse(risposta), function (i, item) {
							if (ent==0) user_ref=item.user_log
							ent=1
							nomef=item.file_user
							url_completo=item.url_completo
							
							html+="<tr id='tr_doc_cant"+item.id+"'>";
								html+="<td> <span id='dele_doc_cant"+item.id+"'></span>";
								if (user_ref==item.id_funzionario) {
									html+="<button type='button' class='btn btn-warning btn-sm' onclick='delerowcant("+item.id+")'>Elimina</button>";
								}
								html+="</td>";
								
								html+="<td>";
									html+=item.name
								html+="</td>";
								html+="<td>"
								html+="<a href='"+url_completo+"' target='_blank'>";
										html+=nomef
								html+="</a>";
								html+="<td style='text-align:center'>"
										html+=item.created_at
								html+="</td>";

							html+="</tr>";
						})
						html+="</tbody>";
					html+="</table>";
				html+="</div>";
				
				$("#bodyvalue").html(html)



			}			
		});	
	},500)
	
}
function newdocincantiere(id_cantiere) {
	html=""
	html+="<center><div class='spinner-border text-secondary' role='status'></div></center>";
	$("#title_doc").html("Nuovo documento per il cantiere")
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
		$("#div_descr_a").remove()
		
		//function set_class_allegati() in demo-config.js
		set_class_allegati.from="allegati_cantiere"

		set_class_allegati.id_cantiere=id_cantiere
		set_class_allegati(); 

		html="<button id='btn_save' disabled type='button' class='btn btn-primary' onclick='saveinfocant()'>Salva</button>";
		//saveinfocant() in demo-config.js

		
		$("#div_save").html(html);		
	})
	.catch(status, err => {
		
		return console.log(status, err);
	})	
		
	
}

function imposta_a(value) {
	testo="";
	if (value=="1") {
		if ($("#list_aziende_e").val().length>0) 
			testo=$('#list_aziende_e option:selected').text();  
		else 
			testo="";
		$("#list_aziende_fissi").val('')
		$("#list_aziende_custom").val('')
	}	
	if (value=="2") {
		if ($("#list_aziende_fissi").val().length>0) 
			testo=$('#list_aziende_fissi option:selected').text();  
		else
			testo=""
		$("#list_aziende_e").val('')
		$("#list_aziende_custom").val('')
	}	
	if (value=="3") {
		if ($("#list_aziende_custom").val().length>0) 
			testo=$('#list_aziende_custom option:selected').text();  
		else
			testo=""
		$("#list_aziende_e").val('')
		$("#list_aziende_fissi").val('')
	}	
	testo=testo.trim()
	$("#azienda").val(testo)
	step2(0)
	
}
function view_form() {
	settori=view_form.settori
	aziende_e=view_form.aziende_e
	aziende_fissi=view_form.aziende_fissi
	aziende_custom=view_form.aziende_custom
	
	html="<div class='container-fluid' id='div_main_value'>";
	html+="<div class='row mb-2'>";
		html+="<div class='col-sm-12'>";
			html+="<div class='form-floating mb-3 mb-md-0'>";
				html+="<select class='form-select' id='list_settori' aria-label='list_settori' name='list_settori' onchange='step2(0)'>";
					html+="<option value=''>Select...</option>";		
					$.each(settori, function (i, item) {

						html+="<option value='"+i+"'>"+item.settore+"</option>";		
					})	
				
				html+="</select>";
				html+="<label for='list_settori'>Settore</label>";
			html+="</div>";
		html+="</div>";
	html+="</div>";	
	html+="<div class='row mb-2'>";	
		html+="<div class='col-sm-4'>";
			//html+="<div class='form-floating mb-3 mb-md-0'>";
				html+="<select class='form-control aziende select2' id='list_aziende_e' aria-label='list_aziende_e' name='list_aziende_e' onchange='imposta_a(1)'>";
					html+="<option value=''>Select...</option>";		
					$.each(aziende_e, function (i, item) {

						html+="<option value='"+item.id_fiscale+"'>"+item.azienda+"</option>";		
					})	
				
				html+="</select>";
				html+="<label for='list_aziende_e'>Azienda Edile</label>";
			//html+="</div>";
		html+="</div>";
		
		html+="<div class='col-sm-4'>";
			//html+="<div class='form-floating mb-3 mb-md-0'>";
				html+="<select class='form-control aziende select2' id='list_aziende_fissi' aria-label='list_aziende_fissi' name='list_aziende_fissi' onchange='imposta_a(2)'>";
					html+="<option value=''>Select...</option>";		
					$.each(aziende_fissi, function (i, item) {

						html+="<option value='"+item.id_fiscale+"'>"+item.azienda+"</option>";		
					})	
				
				html+="</select>";
				html+="<label for='list_aziende_fissi'>Azienda Imp.Fissi</label>";
			//html+="</div>";
		html+="</div>";	
		
		html+="<div class='col-sm-4'>";
			//html+="<div class='form-floating mb-3 mb-md-0'>";
				html+="<select class='form-select aziende' id='list_aziende_custom' aria-label='list_aziende_custom' name='list_aziende_custom' onchange='imposta_a(3)'>";
					html+="<option value=''>Select...</option>";		
					$.each(aziende_custom, function (i, item) {

						html+="<option value='"+item.id+"'>"+item.azienda+"</option>";		
					})	
				
				html+="</select>";
				html+="<label for='list_aziende_custom'>Azienda definita</label>";
			//html+="</div>";
		html+="</div>";			
		

		
		
	html+="</div>";	
	
	html+="<div class='row mb-2' style='display:none' id='div_custom_azienda'>";
		html+="<div class='col-sm-12'>";
			html+="<div class='form-floating mb-3 mb-md-0'>";
				html+="<input class='form-control' id='azienda_def' name='azienda_def' type='text' placeholder='Definizione azienda' maxlength=70 onkeyup=\"$('#azienda').val(this.value)\"/>";
				html+="<label for='azienda'>Azienda</label>";
			html+="</div>";	
		html+="</div>";	
	html+="</div>";	
	html+="<input type='hidden' name='azienda' id='azienda'>";

	
	html+="<a href='javascript:void(0)' onclick='step2(1)' class='link-primary'>Definizione allegato</a>";
	
	
	html+="<div id='div_step2' class='mt-3'></div>";
	html+="<div id='div_step3' class='mt-2'></div>";

	return html	
	
}

function step2(value) {
	
	periodo=$("#periodo").val();
	azienda=$("#azienda").val()
	id_settore=$("#list_settori").val();
	
	console.log("periodo",periodo,"azienda",azienda,"id_settore",id_settore)
	if (id_settore.length==0 || azienda.length==0 || periodo.length!=7) {
		$("#div_step2").empty();
		$("#btn_save").prop("disabled",true);
		if (value=="1") {
			if (periodo.length!=7)
				alert("Definire correttamente un periodo di riferimento!");
			else
				alert("Definire correttamente i dati richiesti!");
		}
		return false
	}

	
	html="<center><div class='spinner-border text-secondary' role='status'></div></center>";

	$("#div_step2").html(html);
	
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
		
		$("#div_step2").html(resp);
		file_user=$("#file_user").val()
		//function set_class_allegati() in demo-config.js
		set_class_allegati.from="allegati"
		set_class_allegati.ref_user=step2.ref_user
		set_class_allegati.periodo=step2.periodo
		set_class_allegati.id_categoria=step2.id_categoria
		set_class_allegati.id_attivita=step2.id_attivita
		set_class_allegati.id_settore=id_settore
		set_class_allegati.azienda=azienda
		set_class_allegati.file_user=file_user
		set_class_allegati(); 
	})
	.catch(status, err => {
		
		return console.log(status, err);
	})	
	

}

function close_doc() {
	//impostato da dash.js o demo-config.js
	if( typeof close_doc.tipo == 'undefined' ) return false
	if (close_doc.tipo=="refresh") $("#frm_dash").submit();	
}


function attiva_confr() {
	if ($("#div_confr").is(":visible")) {
		$("#periodo1").val('');$("#funzionario1").val('');
		$("#confr").val("")
		$("#frm_dash").submit();
	}
	else {
		$("#confr").val("1")
		$('#div_confr').show(150)
	}
}

