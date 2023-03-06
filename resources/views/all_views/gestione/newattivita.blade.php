<div id='div_definition' style='display:none'>
	<form method='post' action="{{ route('definizione_attivita') }}" id='frm_attivita1' name='frm_attivita1' autocomplete="off">	
		<input name="_token" type="hidden" value="{{ csrf_token() }}" id='token_csrf'>
		<input type='hidden' name='edit_elem' id='edit_elem'>

		<div class="container-fluid">
			<hr>
			<h4>
				<font color='red'>Definizione Attività</font>
			</h4>
				<div class="row">
					<div class="col-md-6">
						<div class="form-floating mb-3 mb-md-0">
							<select class="form-select" name="categ" id="categ" required>
								<option value=''>Select...</option>
								@foreach($categorie as $categoria)
									<option value='{{ $categoria->id }}' 	
									<?php 
									//if ($categoria->id==$id) echo " selected ";
										
									?>	
									>{{ $categoria->categoria}}</option>	
								@endforeach					
							
							</select>
							<label for="categ">Categoria di riferimento</label>
						</div>
					</div>
				
					<div class="col-md-4">
						<div class="form-floating">
							<input class="form-control" id="descr_contr" name='descr_contr' type="text" placeholder="Descrizione attività" required maxlength=150 onkeyup="this.value = this.value.toUpperCase();" />
							<label for="descr_contr">Descrizione attività*</label>
						</div>
					</div>
					<div class="col-md-2">
						<button type="submit" class="btn btn-success" >Crea/Modifica Attività</button>
						<button type="button" class="btn btn-secondary" onclick="$('#div_definition').toggle(150)">
						Chiudi
						</button>
						
					</div>
					
				</div>
			<hr>	
		</div>	
	</form>		
</div>