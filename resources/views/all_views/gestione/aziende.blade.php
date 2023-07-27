@extends('all_views.viewmaster.index')

@section('title', 'FirenzeRENDICONTA')
@section('extra_style') 
  <link rel="stylesheet" href="{{ URL::asset('/') }}plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="{{ URL::asset('/') }}plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css"> 

 <!-- per upload -->
  <link href="{{ URL::asset('/') }}dist/css/upload/jquery.dm-uploader.min.css" rel="stylesheet">
  <!-- per upload -->  
  <link href="{{ URL::asset('/') }}dist/css/upload/styles.css?ver=1.1" rel="stylesheet">  

<link href="https://cdn.datatables.net/buttons/1.7.0/css/buttons.dataTables.min.css" rel="stylesheet">
<!-- -->
@endsection



<style>
	tfoot input {
        width: 100%;
        padding: 3px;
        box-sizing: border-box;
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
            <h1 class="m-0">Gestione Aziende</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
			  <li class="breadcrumb-item">Archivi</li>
              <li class="breadcrumb-item active">Aziende</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->


    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
		<?php if (strlen($msg_err)>0) {?> 
			<div class="alert alert-warning" role="alert">
			  <?php echo $msg_err; ?>
			</div>	  
		<?php } ?>	
		<!-- form new voce attestato !-->	
		
		<div id="div_aziende">
			<form method='post' action="{{ route('aziende') }}" id='frm_aziende_dele' name='frm_aziende_dele' autocomplete="off">
				<input type="hidden" value="{{url('/')}}" id="url" name="url">
				<input name="_token" type="hidden" value="{{ csrf_token() }}" id='token_csrf'>
				<div class="row mb-2">
				<input type='hidden' id='az_dele' name='az_dele'>

				<div class="container-fluid mb-3">
					<button type='button' id='btn_associa' class="btn btn-primary" onclick='add_doc()' disabled>Associa un documento all'azienda</button>
					<div class="container mt-4">	
						<div id='div_save'></div>
					</div>
				</div>	



					<div class="col-md-4">
						
							<select class='form-select aziende select2' id='list_aziende_e' aria-label='list_aziende_e' name='list_aziende_e' onchange="check_add(1)">
								<option value=''>Select...</option>
								@foreach($aziende_e as $az_e)
									<option value='{{$az_e->id_fiscale}}'>
									{{$az_e->azienda}}</option>
								@endforeach
							</select>
							<label for='list_aziende_e'>Lista Aziende Edili</label>
						
					</div>

					<div class="col-md-4">
							<select class='form-select aziende select2' id='list_aziende_fissi' aria-label='list_aziende_fissi' name='list_aziende_fissi'  onchange="check_add(2)">
								<option value=''>Select...</option>
								@foreach($aziende_fissi as $az_f)
									<option value='{{$az_f->id_fiscale}}'>
									{{$az_f->azienda}}</option>
								@endforeach
							</select>
							<label for='list_aziende_fissi'>Lista Aziende Impianti Fissi</label>
						
					</div>
					
					<div class="col-md-4">
						<select class='form-select aziende select2' id='dele_azienda' aria-label='dele_azienda' name='dele_azienda' onchange="view_dele(this.value);check_add(3)">
							<option value=''>Select...</option>
								@foreach($aziende_custom as $az_c)
									<option value='{{$az_c->azienda}}'>
									{{$az_c->azienda}}</option>
								@endforeach								
						</select>
						<label for='dele_azienda'>Lista Aziende Definite</label>
						<button type='button' class='btn btn-secondary mt-2' style='display:none'  onclick='dele_az()'  id='btn_dele_az'><i class='fa fa-trash'></i> Elimina</button>
					</div>				
					
						
				</div>
				<hr>
			</form>	
		</div>
		
		
		<div id="div_new_az">
			<form method='post' action="{{ route('aziende') }}" id='frm_aziende' name='frm_aziende' autocomplete="off">
				<input name="_token" type="hidden" value="{{ csrf_token() }}" id='token_csrf'>
				<div class="row mb-2">

					<div class="col-md-4">
						<div class="form-floating mb-3 mb-md-0">
							<input class='form-control' id='azienda_def' name='azienda_def' type='text' maxlength=100 required />
							<label for='azienda_def'>Definizione Nuova Azienda</label>
						</div>
					</div>
				</div>

				<button type="submit" class="btn btn-primary">Definizione nuova azienda</button>
				
			</form>	
		</div>
		
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
	<!--select2 !-->
	<script src="{{ URL::asset('/') }}plugins/select2/js/select2.full.min.js"></script>	

	<!-- per upload -->
	<script src="{{ URL::asset('/') }}dist/js/upload/jquery.dm-uploader.min.js"></script>
	<script src="{{ URL::asset('/') }}dist/js/upload/demo-ui.js?ver=1.300"></script>
	<script src="{{ URL::asset('/') }}dist/js/upload/demo-config.js?ver=2.409"></script>		
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
	
	

	<script src="{{ URL::asset('/') }}dist/js/aziende.js?ver=1.449"></script>

@endsection