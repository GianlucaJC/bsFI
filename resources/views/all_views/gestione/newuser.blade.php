<div id='div_definition' style='display:none'>
	<form method='post' action="{{ route('definizione_utenti') }}" id='frm_user1' name='frm_user1' autocomplete="off">	
		<input name="_token" type="hidden" value="{{ csrf_token() }}" id='token_csrf'>
		<input type='hidden' name='edit_elem' id='edit_elem'>

		<div class="container-fluid">
			<hr>
			<h4>
				<font color='red'>Definizione Profilo</font>
			</h4>
				<div class="row">
					<div class="col-md-6">
						<div class="form-floating mb-3 mb-md-0">
							<select class="form-select" name="profilo_user" id="profilo_user" required>
									<option value='1'
									>Admin</option>	
								
									<option value='3'
									>User</option>	
							
							</select>
							<label for="profilo_user">Profilo Utente*</label>
						</div>
					</div>
				

					<div class="col-md-2">
						<button type="submit" class="btn btn-success" >Assegna profilo</button>
						<button type="button" class="btn btn-secondary" onclick="$('#edit_elem').val('');$('#div_definition').toggle(150)">
						Chiudi
						</button>
						
					</div>
					
				</div>
			<hr>	
		</div>	
	</form>		
</div>