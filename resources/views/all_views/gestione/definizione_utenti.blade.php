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
            <h1 class="m-0">UTENTI</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
			  <li class="breadcrumb-item">Archivi</li>
              <li class="breadcrumb-item active">Utenti</li>
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
		
		@include('all_views.gestione.newuser')
		
		 

		<form method='post' action="{{ route('definizione_utenti') }}" id='frm_utenti' name='frm_utenti' autocomplete="off">
			<input name="_token" type="hidden" value="{{ csrf_token() }}" id='token_csrf'>
			<input type='hidden' name='user_abilita' id='user_abilita'>


        <div class="row">
          <div class="col-md-12">
		  
				<table id='tbl_list_utenti' class="display">
					<thead>
						<tr>
							<th>TESSERA</th>
							<th>Utente</th>
							<th>Profilo</th>
							<th>Operazioni</th>
						</tr>
					</thead>
					<tbody>
						@foreach($definizione_utenti as $utenti)
							<tr>
								<td>
								@if ($utenti->role_id!="1" && $utenti->role_id!="3") <del>
									{{ $utenti->n_tessera }}</del>
								@else  {{ $utenti->n_tessera }}
								@endif
								</td>
								<td>
									<span id='profilo{{$utenti->id}}' data-descr='{{$utenti->role_id}}'></span>
									@if ($utenti->role_id=="1")
										<p class="font-weight-bold">{{ $utenti->utentefillea }}</p>
									@elseif ($utenti->role_id=="3") 
										<p class="font-weight">{{$utenti->utentefillea }}</p>
									@else 
										<p class="font-italic">{{$utenti->utentefillea }}</p>
									@endif
								</td>
								<td>
									@if ($utenti->role_id=="1") 
										<p class="font-weight-bold">Admin</p>
									@elseif ($utenti->role_id=="3") 
										<p class="font-weight">User</p>
									@else	
										<p class="font-italic">Non abilitato al servizio</p>
									@endif
								</td>	
								<td>
								@if ($utenti->role_id=="1" || $utenti->role_id=="3")  	
									<a href='#' title='Modifica profilo' onclick="edit_elem({{$utenti->id}},{{$utenti->idu}})">
										<button type="button" class="btn btn-info" alt='Edit'><i class="fas fa-user-edit"></i></button>
									</a>
								@endif
								@if ($utenti->role_id=="1" || $utenti->role_id=="3")  
									<a href='javascript:(0)' onclick="dele_element({{$utenti->idu}})" title='Disabilita servizio'>
										<button type="submit" name='dele_ele' class="btn btn-danger"><i class="fas fa-user-slash"></i></button>	
									</a>
								@else
									<a href='javascript:(0)' onclick="abilita({{$utenti->id}})" title='Abilita servizio'>
										<button name='abilita' class="btn btn-success" ><i class="fas fa-user-plus"></i></button>	
									</a>
								@endif
								</td>	
							</tr>
						@endforeach
						
					</tbody>
					<tfoot>
						<tr>
							<th>TESSERA</th>
							<th>Utente</th>
							<th>Profilo</th>
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
	
	

	<script src="{{ URL::asset('/') }}dist/js/utenti.js?ver=1.47"></script>

@endsection