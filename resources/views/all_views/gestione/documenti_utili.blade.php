@extends('all_views.viewmaster.index')

@section('title', 'FirenzeRENDICONTA')
@section('extra_style') 
 <!-- per upload -->
  <link href="{{ URL::asset('/') }}dist/css/upload/jquery.dm-uploader.min.css" rel="stylesheet">
  <!-- per upload -->  
  <link href="{{ URL::asset('/') }}dist/css/upload/styles.css?ver=1.1" rel="stylesheet">  
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
</style>
@section('content_main')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Documenti Utili</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
			  <li class="breadcrumb-item">Archivi</li>
              <li class="breadcrumb-item active">Documenti Utili</li>
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
		
		 

		<form method='post' action="{{ route('documenti_utili') }}" id='frm_documenti' name='frm_documenti' autocomplete="off">
			<input name="_token" type="hidden" value="{{ csrf_token() }}" id='token_csrf'>
			<input type="hidden" value="{{url('/')}}" id="url" name="url">
			
        <div class="row">
          <div class="col-md-12">
		  
				<table id='tbl_list_doc' class="display">
					<thead>
						<tr>
							<th>Data ora</th>
							<th>Utente</th>
							<th style="max-width:400px">Documento</th>
							<th>Categoria</th>
							<th>Elimina</th>
						</tr>
					</thead>
					<tbody>

						@foreach($documenti_utili as $documento)
							<tr>
								<td>
									{{ $documento->created_at}}
								</td>
								<td>
									{{ $utenti[$documento->id_funzionario] }}
								</td>

								<td style="max-width:400px">
									<a href="{{$documento->url_completo}}" target='_blank'>
										@if (strlen($documento->file_user)==0)
											{{ $documento->filename }}	
										@else
											{{ $documento->file_user }}
										@endif
									</a>
								</td>
								<td>
									{{ $documento->descrizione }}
								</td>
								<td>
								@if ($documento->id_funzionario==$ref_user)
									<a href='#' onclick="dele_element({{$documento->id}})">
										<button type="submit" name='dele_ele' class="btn btn-danger"><i class="fas fa-trash"></i></button>	
									</a>
									@endif
								</td>

							</tr>
						@endforeach
						
					</tbody>
					<tfoot>
						<tr>
							<th>Data ora</th>
							<th>Utente</th>
							<th style="max-width:400px">Documento</th>
							<th>Categoria</th>
							<th></th>
						</tr>
					</tfoot>					
				</table>
				<input type='hidden' id='dele_contr' name='dele_contr'>
				<input type='hidden' id='restore_contr' name='restore_contr'>
			
          </div>
			<button type="button" class="btn btn-primary btn-lg" onclick='newdoc()'>Nuovo Documento</button>
        </div>
	
		</form>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <!-- Modal -->
<div class="modal fade bd-example-modal-lg" id="modalvalue" tabindex="-1" role="dialog" aria-labelledby="title_doc" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="title_doc">Inserimento dati</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id='bodyvalue'>
        ...
      </div>
		<div class="modal-body">
		
			<div class="col-md-12">
				<div class="form-floating mb-3 mb-md-0">
					<select class="form-select" name="categ" id="categ" required>
						<option value=''>Select...</option>
						@foreach($cat_doc_utili as $categoria)
							<option value='{{ $categoria->id }}'> 	
							{{ $categoria->descrizione}}</option>	
						@endforeach					
					
					</select>
					<label for="categ">Categoria di riferimento*</label>
				</div>
			</div>
		
		</div>
					
	  <div id='div_wait' class='mb-3'></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal" id='btn_close' onclick='close_doc()'>Chiudi</button>
        <div id='div_save'></div>
      </div>
	  
    </div>
  </div>
</div>
  
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
	
	

	<script src="{{ URL::asset('/') }}dist/js/documenti_utili.js?ver=1.60"></script>
	
	<!-- per upload -->
	<script src="{{ URL::asset('/') }}dist/js/upload/jquery.dm-uploader.min.js"></script>
	<script src="{{ URL::asset('/') }}dist/js/upload/demo-ui.js?ver=1.301"></script>
	<script src="{{ URL::asset('/') }}dist/js/upload/demo-config.js?ver=2.395"></script>		

@endsection