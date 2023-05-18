@extends('all_views.viewmaster.index')

@section('title', 'FirenzeRENDICONTA')
@section('extra_style') 
<!-- x button export -->

<link href="https://cdn.datatables.net/buttons/1.7.0/css/buttons.dataTables.min.css" rel="stylesheet">
<!-- -->
@endsection



<style>
	tfoot input {
        width: 100%;
        padding: 3px;
        box-sizing: border-box;
    }
	input[readonly].classname{
	  background-color:transparent;
	  border: 0;
	  font-size: 1em;
	}	
</style>
@section('content_main')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Assegnazioni Aziende</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
			  <li class="breadcrumb-item">Archivi</li>
              <li class="breadcrumb-item active">Assegnazioni Aziende</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->


    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
		<!-- form new voce attestato !-->	
		<?php if (strlen($msg_err)>0) {?> 
			<div class="alert alert-warning" role="alert">
			  <?php echo $msg_err; ?>
			</div>	  
		<?php } ?>		
		<div id="div_new_ass" style='display:none'>
			<form method='post' action="{{ route('assegnazioni') }}" id='frm_new' name='frm_new' autocomplete="off">
				<input name="_token" type="hidden" value="{{ csrf_token() }}" id='token_csrf'>
				<input type='hidden' name='user_ass' id='user_ass'>
				
				<div class="row mb-2">

					<div class="col-md-4">
						<div class="form-floating mb-3 mb-md-0">
							<select class='form-select aziende' id='list_aziende_e' aria-label='list_aziende_e' name='list_aziende_e' onchange='set_a(1)'>
								<option value=''>Select...</option>
								@foreach($aziende_e as $az_e)
									<option value='{{$az_e->id_fiscale}}'>
									{{$az_e->azienda}}</option>
								@endforeach
							</select>
							<label for='list_aziende_e'>Aziende Edili</label>
						</div>
					</div>

					<div class="col-md-4">
						<div class="form-floating mb-3 mb-md-0">
							<select class='form-select aziende' id='list_aziende_fissi' aria-label='list_aziende_fissi' name='list_aziende_fissi' onchange='set_a(2)'>
								<option value=''>Select...</option>
								@foreach($aziende_fissi as $az_f)
									<option value='{{$az_f->id_fiscale}}'>
									{{$az_f->azienda}}</option>
								@endforeach
							</select>
							<label for='list_aziende_fissi'>Aziende Impianti Fissi</label>
						</div>
					</div>
					
					<div class="col-md-4">
						<div class="form-floating mb-3 mb-md-0">
							<select class='form-select aziende' id='list_aziende_custom' aria-label='list_aziende_custom' name='list_aziende_custom' onchange='set_a(3)'>
								<option value=''>Select...</option>
								
									<option value=''>
									</option>
								
							</select>
							<label for='list_aziende_custom'>Aziende Definite</label>
						</div>
					</div>				
					
						
				</div>
				<p>Azienda assegnata: <input type="text" class="classname" readonly  name='azienda' id='azienda' value='Non assegnata'/>
				<input type='hidden' name='id_fiscale' id='id_fiscale'>
				</p>
				<button type="button" class="btn btn-primary" onclick="real_ass()">Assegnazione azienda selezionata</button>
				<button type="button" class="btn btn-secondary ml-2" onclick="close_new()">Chiudi</button>

				<hr>
			</form>	
		</div>

		 

		<form method='post' action="{{ route('assegnazioni') }}" id='frm_assegnazioni' name='frm_assegnazioni' autocomplete="off">
			<input name="_token" type="hidden" value="{{ csrf_token() }}" id='token_csrf'>
		
		<input type='hidden' name='az_dele' id='az_dele'>
		<input type='hidden' name='idus_dele' id='idus_dele'>		
		<div class="row">
		  <div class="col-md-12">
		  
			<table id='tbl_assegnazioni' class="display">
				<thead>
					<tr>
						<th>Utente</th>
						<th>Aziende assegnate</th>
						<th>Operazioni</th>
					</tr>
				</thead>
				<tbody>
					@php($rif_id=0)

					@foreach($utenti as $info)	
						<tr>
							<td>
								{{$info->name}}
							</td>
							<td>
								<?php 
									$az="";
									if (array_key_exists($info->id,$user_az)){
										for ($sca=0;$sca<=count($user_az[$info->id])-1;$sca++) {
											$az=$user_az[$info->id][$sca];
											echo "<button type='button' class='btn btn-secondary ml-2'  data-azienda=\"$az\" data-idus='".$info->id."' id='btn_dele$rif_id'><i class='fa fa-trash' onclick='dele_az($rif_id)' ></i> $az</button>";
											$rif_id++;
										}
									}
									
								?>
								
							<td>
								<button type="button" class="btn btn-success"  onclick="assegna({{$info->id}})">Aggiungi Azienda</button>
							</td>
						</tr>
					@endforeach
					
				</tbody>
				<tfoot>
					<tr>
						<th>Utente</th>
						<th>Aziende assegnate</th>
						<th></th>
					</tr>
				</tfoot>					
			</table>
			<input type='hidden' id='dele_contr' name='dele_contr'>
			<input type='hidden' id='restore_contr' name='restore_contr'>
			
		  </div>

		</div>
	
		</form>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
 @endsection
 
 @section('content_plugin')
	<!-- jQuery -->
	<script src="plugins/jquery/jquery.min.js"></script>
	<!-- Bootstrap 4 -->
	<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
	<!-- AdminLTE App -->
	<script src="dist/js/adminlte.min.js"></script>


	
	<!-- inclusione standard
		per personalizzare le dipendenze DataTables in funzione delle opzioni da aggiungere: https://datatables.net/download/
	!-->
	
	<!-- dipendenze DataTables !-->
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.12.1/b-2.2.3/b-colvis-2.2.3/b-html5-2.2.3/b-print-2.2.3/datatables.min.css"/>
		 
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
		<script type="text/javascript" src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.12.1/b-2.2.3/b-colvis-2.2.3/b-html5-2.2.3/b-print-2.2.3/datatables.min.js"></script>
	<!-- fine DataTables !-->
	
	

	<script src="{{ URL::asset('/') }}dist/js/assegnazioni.js?ver=1.416"></script>

@endsection