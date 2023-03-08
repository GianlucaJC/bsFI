<?php
use App\Models\User;
	$id = Auth::user()->id;
	$user = User::find($id);
?>

<style>

 #tb_attivita td {
  padding: 0.06rem;
  vertical-align: top;
  border-top: 1px solid #dee2e6;
}
</style>
@extends('all_views.viewmaster.index')

@section('title', 'FirenzeRENDICONTA')

@section('notifiche') 

	@if (1==1)
      <li class="nav-item dropdown notif" onclick="azzera_notif()">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-bell"></i>
          <span class="badge badge-warning navbar-badge"></span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-header">Avvisi</span>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-file-signature"></i>  Elenco avvisi...
            <span class="float-right text-muted text-sm"></span>
          </a>
          <div class="dropdown-divider"></div>

          <div class="dropdown-divider"></div>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">Vai al dettaglio</a>
        </div>
      </li>
	@endif  
@endsection

@section('content_main')
  <input name="_token" type="hidden" value="{{ csrf_token() }}" id='token_csrf'>
  <input type="hidden" value="{{url('/')}}" id="url" name="url">
  <!-- Content Wrapper. Contains page content -->


  
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">DASHBOARD - ATTIVITA'</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
              
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
		<?php
			
			$att=1;
			foreach($categorie as $categoria=>$categ) {
				if (isset($attivita_index[$categoria])) {?>
				
				<div class="card">
					<div class="card-header border-0">
						<h3 class="card-title"><font color='red'>{{$categ}}</font></h3>
						<div class="card-tools">
						  <a href="#" class="btn btn-tool btn-sm">
							<i class="fas fa-download"></i>
						  </a>
						  <a href="#" class="btn btn-tool btn-sm">
							<i class="fas fa-bars"></i>
						  </a>
						</div>
					</div>

					<div class="card-body table-responsive p-0">
					<table id='tb_attivita' class="table table-striped table-valign-middle" style="padding:0.15rem">
					  <thead>
					  <tr>
						<th></th>
						<?php
							foreach($settori as $id_settore=>$v) {
								$obj=$settori[$id_settore];
								echo "<th style='background-color:".$obj['bcolor'].";color:".$obj['color']."'>";
									echo $obj['settore'];
								echo "</th>";	
							}
						?>

						<th style=''>TOTALE</th>
						<th>More</th>
					  </tr>
					  </thead>
					  <tbody>
					  <?php
						$attivita=$attivita_index[$categoria];
						for ($sca=0;$sca<=count($attivita)-1;$sca++) {
							if (!isset($attivita[$sca]['descrizione'])) continue;
							
							$descr=$attivita[$sca]['descrizione'];
							echo "<tr>";
								echo "<td>";
								  echo "<b>$descr</b>";
								echo "</td>";
								foreach($settori as $id_settore=>$v) {
									echo "<td>";
									  echo "<a href='#' class='text-muted'>";
										echo "";
									  echo "</a>";
									echo "</td>";
								}	
							echo "<td></td>";
							echo "<td>";
								echo "<a href='#' class='text-muted'>";
									echo "<i class='fas fa-search'></i>";
								echo "</a>";
							echo "</td>";

							echo "</tr>";
							
						}?>
						
					  </tbody>
					</table>
				  </div>
				 
				</div>
			
				<?php 
				}
			
			} ?>


	


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
	
	<script src="{{ URL::asset('/') }}dist/js/dash.js?ver=1.05"></script>
	
@endsection
