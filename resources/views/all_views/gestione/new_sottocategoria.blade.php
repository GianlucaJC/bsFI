<div id='div_definition' style='display:none'>
	<form method='post' action="{{ route('categorie_documenti') }}" id='frm_categ1' name='frm_categ1' autocomplete="off">	
		<input name="_token" type="hidden" value="{{ csrf_token() }}" id='token_csrf'>
		<input type='hidden' name='edit_elem' id='edit_elem'>

		<div class="container-fluid">
			<hr>
			<h4>
				<font color='red'>Definizione Categoria</font>
			</h4>

				<div class="row mt-2">
					<div class="col-md-12">
						<div class="form-floating">
							<input class="form-control" id="descr_contr" name='descr_contr' type="text" placeholder="Descrizione attività" required maxlength=150 onkeyup="this.value = this.value.toUpperCase();" />
							<label for="descr_contr">Descrizione Categoria*</label>
						</div>
					</div>
				</div>
				<div class="row mt-2">
					<div class="col-md-3">
						<button type="submit" class="btn btn-success" >Crea/Modifica Categoria</button>
						<button type="button" class="btn btn-secondary" onclick="$('#div_definition').toggle(150)">
						Chiudi
						</button>
						
					</div>
					
				</div>
			<hr>	
		</div>	
	</form>		
</div>