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
</style>
@section('content_main')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Documenti</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
			  <li class="breadcrumb-item">Archivi</li>
              <li class="breadcrumb-item active">Documenti</li>
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
		
		 

		<form method='post' action="{{ route('documenti') }}" id='frm_documenti' name='frm_documenti' autocomplete="off">
			<input name="_token" type="hidden" value="{{ csrf_token() }}" id='token_csrf'>
			
        <div class="row">
          <div class="col-md-12">
		  
				<table id='tbl_list_doc' class="display">
					<thead>
						<tr>
							<th>Data ora</th>
							<th>Utente</th>
							<th>Periodo</th>
							<th>Categoria</th>
							<th>Attivita</th>
							<th>Settore</th>
							<th>Azienda</th>
							<th>Documento</th>
						</tr>
					</thead>
					<tbody>

						@foreach($documenti as $documento)
							<tr>
								<td>
									{{ $documento->created_at}}
								</td>
								<td>
									{{ $utenti[$documento->id_funzionario] }}
								</td>
								<td>
									{{ $documento->periodo }}
								</td>
								<td>
									{{ $categorie[$documento->id_categoria] }}
								</td>	
								<td>
									{{ $attivita[$documento->id_attivita] }}
								</td>	
								<td>
									{{ $settori[$documento->id_settore]['settore'] }}
								</td>	
								<td>
									{{ $documento->azienda }}
								</td>

								<td>
									<a href="{{$documento->url_completo}}" target='_blank'>
										@if (strlen($documento->file_user)==0)
											{{ $documento->filename }}	
										@else
											{{ $documento->file_user }}
										@endif
									</a>
								</td>	

							</tr>
						@endforeach
						
					</tbody>
					<tfoot>
						<tr>
							<th>Data ora</th>
							<th>Utente</th>
							<th>Periodo</th>
							<th>Categoria</th>
							<th>Attivita</th>
							<th>Settore</th>
							<th>Azienda</th>
							<th>Documento</th>
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
	
	

	<script src="{{ URL::asset('/') }}dist/js/documenti.js?ver=1.50"></script>

@endsection