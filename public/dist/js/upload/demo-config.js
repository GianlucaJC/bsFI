csv_send=false

$(function(){
 //set_class_allegati(0)
 
});


function set_class_allegati() {
  /*
   * For the sake keeping the code clean and the examples simple this file
   * contains only the plugin configuration & callbacks.
   * 
   * UI functions ui_* can be located in: demo-ui.js
   */
  	
	
	/*
		I seguenti dati provengono dal file dash.js (o da documenti_utili.js, aziende.js o da qualiasi altro js che ne abbia bisogno) che li preimposta.
		A seconda di come viene valorizzato [from] si predispongono
		una serie di variabili da passare via Ajax tramite la function saveinfo() o saveinfocant() o altre function che si vogliono aggiungere.
		Queste function vengono invocate da bottoni 'salva', creati (da dash.js) iniettando html nella finestra modal (in dashboard.blade.php) nel div_save
	
	*/
	from=set_class_allegati.from

	if (from=="allegati") {
		file_user=set_class_allegati.file_user
		ref_user=set_class_allegati.ref_user
		periodo=set_class_allegati.periodo
		id_categoria=set_class_allegati.id_categoria
		id_attivita=set_class_allegati.id_attivita
		id_settore=set_class_allegati.id_settore
		azienda=set_class_allegati.azienda

		extraData= {
		  "from":from,
		  "ref_user":ref_user,
		  "periodo":periodo,
		  "id_categoria":id_categoria,
		  "id_attivita":id_attivita,
		  "id_settore":id_settore
		}
	}
	
	if (from=="allegati_cantiere") {
		file_user=set_class_allegati.file_user
		id_cantiere=set_class_allegati.id_cantiere


		extraData= {
		  "from":from,
		  "id_cantiere":id_cantiere,
		}
	}

	if (from=="allegati_aziende") {
		
		file_user=set_class_allegati.file_user
		id_azienda=set_class_allegati.id_azienda
		azienda=set_class_allegati.azienda


		extraData= {
		  "from":from,
		  "id_azienda":id_azienda,
		  "azienda":azienda,
		}
	}

	if (from=="allegati_utili") {
		file_user=set_class_allegati.file_user

		extraData= {
		  "from":from
		  }
	}  


  base_path = $("#url").val();

  $('#drag-and-drop-zone').dmUploader({ //
    url: base_path+'/upload.php',
	extraData:extraData,
	
	extFilter: ["pdf","doc","docx","jpg","png","odt"],
	
    maxFileSize: 80000000, // 8 Megs 
    onDragEnter: function(){
      // Happens when dragging something over the DnD area
      this.addClass('active');
    },
    onDragLeave: function(){
      // Happens when dragging something OUT of the DnD area
      this.removeClass('active');
    },
    onInit: function(){
      // Plugin is ready to use
      ui_add_log('Plugin Avviato :)', 'info');
    },
    onComplete: function(){
      // All files in the queue are processed (success or error)
      ui_add_log('Tutti i trasferimenti in sospeso sono terminati');
    },
    onNewFile: function(id, file){
      // When a new file is added using the file selector or the DnD area
      ui_add_log('Nuovo file aggiunto #' + id);
      ui_multi_add_file(id, file);
    },
    onBeforeUpload: function(id){
	  $("#div_img").empty();
      // about tho start uploading a file
      ui_add_log('Inizio upload di #' + id);
      ui_multi_update_file_status(id, 'uploading', 'Uploading...');
      ui_multi_update_file_progress(id, 0, '', true);
    },
    onUploadCanceled: function(id) {
      // Happens when a file is directly canceled by the user.
      ui_multi_update_file_status(id, 'warning', 'Cancellato da utente');
      ui_multi_update_file_progress(id, 0, 'warning', false);
    },
    onUploadProgress: function(id, percent){
      // Updating file progress
      ui_multi_update_file_progress(id, percent);
    },
    onUploadSuccess: function(id, data){
      // A file was successfully uploaded
	  
	  fx=data.path
	  
	  dx=JSON.stringify(data)
	  console.log(dx)
      ui_add_log('Server Response for file #' + id + ': ' + JSON.stringify(data));
      ui_add_log('Upload del file #' + id + ' COMPLETATO', 'success');
      ui_multi_update_file_status(id, 'success', 'Upload Completato');
      ui_multi_update_file_progress(id, 100, 'success', false);
	  
	  $("#btn_save").removeAttr("disabled");
	  $('#div_main_value *').prop('disabled',true);
	  $("#file_user").removeAttr("disabled");
	  if (from=="allegati") {
		  saveinfo.filename=data.filename
		  saveinfo.file_user=file_user
		  saveinfo.ref_user=ref_user
		  saveinfo.periodo=periodo
		  saveinfo.id_categoria=id_categoria
		  saveinfo.id_attivita=id_attivita
		  saveinfo.id_settore=id_settore
		  saveinfo.azienda=azienda
	  }
	  if (from=="allegati_cantiere") {
		  saveinfocant.filename=data.filename
		  saveinfocant.file_user=file_user
		  saveinfocant.id_cantiere=id_cantiere
		  close_doc.tipo="refresh"
	  }

	  if (from=="allegati_aziende") {
		  saveinfoazi.filename=data.filename
		  saveinfoazi.file_user=file_user
		  saveinfoazi.id_azienda=set_class_allegati.id_azienda
		  saveinfoazi.azienda=set_class_allegati.azienda
	  }
	  if (from=="allegati_utili") {
		  saveinfodoc.filename=data.filename
		  saveinfodoc.file_user=file_user
		  close_doc.tipo="refresh"
	  }	 
	 
    },
    onUploadError: function(id, xhr, status, message){
      ui_multi_update_file_status(id, 'danger', message);
      ui_multi_update_file_progress(id, 0, 'danger', false);  
    },
    onFallbackMode: function(){
      // When the browser doesn't support this plugin :(
      ui_add_log('Il plug-in non può essere utilizzato qui', 'danger');
    },
    onFileSizeError: function(file){
      ui_add_log('Il File \'' + file.name + '\' Non può essere aggiunto: Limite dimensione superato', 'danger');
    }
  });	
}

function saveinfo() {
	if( typeof saveinfo.filename == 'undefined' ) {
		$("#btn_save").prop("disabled",true);
		console.log("false");
		return false
	}	
	file_user=$("#file_user").val()
	if (file_user.length==0) {
		alert("Definire un nome per l'allegato da inviare al server!");
		return false
	}

	$("#btn_save").prop("disabled",true);
	base_path = $("#url").val();
	let CSRF_TOKEN = $("#token_csrf").val();
	
	html="<span role='status' aria-hidden='true' class='spinner-border spinner-border-sm'></span> Attendere...";

	$("#div_step3").html(html);
	setTimeout(function(){
	
		fetch(base_path+'/update_doc', {
			method: 'post',
			//cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached		
			headers: {
			  "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
			},
			body: '_token='+ CSRF_TOKEN+'&ref_user='+saveinfo.ref_user+'&periodo='+saveinfo.periodo+'&id_categoria='+saveinfo.id_categoria+'&id_attivita='+saveinfo.id_attivita+'&id_settore='+saveinfo.id_settore+'&azienda='+saveinfo.azienda+'&filename='+saveinfo.filename+"&file_user="+file_user
		})
		.then(response => {
			if (response.ok) {
			   return response.json();
			}
		})
		.then(resp=>{
			$("#div_step3").empty();			
			if (resp.status=="KO") {
				alert("Problemi occorsi durante il salvataggio.\n\nDettagli:\n"+resp.message);
				return false;
			}
			$("#btn_save").hide(150)
			close_doc.tipo="refresh"
			//$("#doc"+ref_row).remove()
		})
		.catch(status, err => {
			$("#div_step3").empty();
			return console.log(status, err);
		})	
	
	
	},1000);	
}


function saveinfocant() {
	if( typeof saveinfocant.filename == 'undefined' ) {
		$("#btn_save").prop("disabled",true);
		console.log("false");
		return false
	}	
	file_user=$("#file_user").val()
	if (file_user.length==0) {
		alert("Definire un nome per l'allegato da inviare al server!");
		return false
	}

	$("#btn_save").prop("disabled",true);
	base_path = $("#url").val();
	let CSRF_TOKEN = $("#token_csrf").val();
	
	html="<span role='status' aria-hidden='true' class='spinner-border spinner-border-sm'></span> Attendere...";

	$("#div_save").html(html);
	setTimeout(function(){
	
		fetch(base_path+'/update_doc_cant', {
			method: 'post',
			//cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached		
			headers: {
			  "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
			},
			body: '_token='+ CSRF_TOKEN+'&filename='+saveinfocant.filename+"&file_user="+file_user+"&id_cantiere="+saveinfocant.id_cantiere
		})
		.then(response => {
			if (response.ok) {
			   return response.json();
			}
		})
		.then(resp=>{
			$("#div_save").empty();			
			if (resp.status=="KO") {
				alert("Problemi occorsi durante il salvataggio.\n\nDettagli:\n"+resp.message);
				return false;
			}
			close_doc.tipo="refresh"
		})
		.catch(status, err => {
			return console.log(status, err);
		})	
	
	
	},1000);	
}

function saveinfodoc() {
	if( typeof saveinfodoc.filename == 'undefined' ) {
		$("#btn_save").prop("disabled",true);
		console.log("false");
		return false
	}	
	file_user=$("#file_user").val()
	if (file_user.length==0) {
		alert("Definire un nome per l'allegato da inviare al server!");
		return false
	}
	categ=$("#categ").val()
	if (categ.length==0) {
		alert("Definire una categoria di riferimento!");
		return false
	}

	$("#btn_save").prop("disabled",true);
	base_path = $("#url").val();
	let CSRF_TOKEN = $("#token_csrf").val();
	
	html="<span role='status' aria-hidden='true' class='spinner-border spinner-border-sm'></span> Attendere...";

	$("#div_save").html(html);
	setTimeout(function(){
	
		fetch(base_path+'/update_doc_utili', {
			method: 'post',
			//cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached		
			headers: {
			  "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
			},
			body: '_token='+ CSRF_TOKEN+'&filename='+saveinfodoc.filename+"&file_user="+file_user+"&categ="+categ
		})
		.then(response => {
			if (response.ok) {
			   return response.json();
			}
		})
		.then(resp=>{
			$("#div_save").empty();			
			if (resp.status=="KO") {
				alert("Problemi occorsi durante il salvataggio.\n\nDettagli:\n"+resp.message);
				return false;
			}
			close_doc.tipo="refresh"
		})
		.catch(status, err => {
			return console.log(status, err);
		})	
	
	
	},1000);	
}

function saveinfoazi() {
	if( typeof saveinfoazi.filename == 'undefined' ) {
		$("#btn_save").prop("disabled",true);
		console.log("false");
		return false
	}	
	file_user=$("#file_user").val()
	if (file_user.length==0) {
		alert("Definire un nome per l'allegato da inviare al server!");
		return false
	}

	$("#btn_save").prop("disabled",true);
	base_path = $("#url").val();
	let CSRF_TOKEN = $("#token_csrf").val();
	
	html="<span role='status' aria-hidden='true' class='spinner-border spinner-border-sm'></span> Attendere...";

	$("#div_save").html(html);
	setTimeout(function(){
	
		fetch(base_path+'/update_file_azi', {
			method: 'post',
			//cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached		
			headers: {
			  "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
			},
			body: '_token='+ CSRF_TOKEN+'&filename='+saveinfoazi.filename+"&file_user="+file_user+"&id_azienda="+saveinfoazi.id_azienda+"&azienda="+saveinfoazi.azienda
		})
		.then(response => {
			if (response.ok) {
			   return response.json();
			}
		})
		.then(resp=>{
			$("#div_save").empty();			
			if (resp.status=="KO") {
				alert("Problemi occorsi durante il salvataggio.\n\nDettagli:\n"+resp.message);
				return false;
			}
		})
		.catch(status, err => {
			return console.log(status, err);
		})	
	
	
	},1000);	
}