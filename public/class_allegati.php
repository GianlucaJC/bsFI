<!-- ALLEGATI -->

<!-- ref https://github.com/danielm/uploader -->

<svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
  <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
	<path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
  </symbol>
  <symbol id="info-fill" fill="currentColor" viewBox="0 0 16 16">
	<path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
  </symbol>
  <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
	<path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
  </symbol>
</svg>	
<div id="sez_allegati" style="" class="mt-2">
	
	<div id="div_send_allegati">
		<div class="row mt-2">
			<div class="col-md-6 col-sm-12">
			  
			  <!-- Our markup, the important part here! -->
			  <div id="drag-and-drop-zone" class="dm-uploader p-5">
				<h3 class="mb-5 mt-5 text-muted">Trascina il file quì</h3>

				<div class="btn btn-primary btn-block mb-5">
					<span>...altrimenti sfoglia</span>
					<input type="file" title="Click to add Files" />
				</div>
			  </div><!-- /uploader -->

			</div>
			<div class="col-md-6 col-sm-12">
			  <div class="card h-100">
				<div class="card-header">
				  File Inviati
				</div>

				<ul class="list-unstyled p-2 d-flex flex-column col" id="files">
				  <li class="text-muted text-center empty">Nessun File inviato.</li>
				</ul>
			  </div>
			</div>
		</div><!-- /file list -->				  



		<div class="row" style="display:none">
			<div class="col-12">
			   <div class="card h-100">
				<div class="card-header">
				  Messaggi di debug
				</div>

				<ul class="list-group list-group-flush" id="debug">
				  <li class="list-group-item text-muted empty">Loading plugin....</li>
				</ul>
			  </div>
			</div>
		</div> <!-- /debug -->
	</div>
	<hr>

	 <div class="form-group">
		<label for="file_user">Descrizione allegato</label>
		<input type="text" class="form-control" id="file_user" name="file_user" aria-describedby="File User" placeholder="Assegnazione nome allegato" maxlength=100>
		<div id='div_descr_a'>
			<small  class="form-text text-muted">Servirà per identificare meglio il documento nella sezione relativa.</small>
		</div>
	</div>
	
	
</div>


<!-- File item template -->
<script type="text/html" id="files-template">
  <li class="media">
	<div class="media-body mb-1">
	  <p class="mb-2">
		<strong>%%filename%%</strong> - Status: <span class="text-muted">Waiting</span>
	  </p>
	  <div class="progress mb-2">
		<div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" 
		  role="progressbar"
		  style="width: 0%" 
		  aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
		</div>
	  </div>
	  <hr class="mt-1 mb-1" />
	</div>
  </li>
</script>

<!-- Debug item template -->
<script type="text/html" id="debug-template">
  <li class="list-group-item text-%%color%%"><strong>%%date%%</strong>: %%message%%</li>
</script>