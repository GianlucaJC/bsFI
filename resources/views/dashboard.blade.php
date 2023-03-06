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
@extends('viewmaster.index')

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
			$attivita="";
			for ($att=1;$att<=5;$att++) {
				if ($att==1) $attivita="Collettiva";
				if ($att==2) $attivita="Individuale";
				if ($att==3) $attivita="Servizi";
				if ($att==4) $attivita="Fillea";
				if ($att==5) $attivita="Altre";
			
			?>
            <div class="card">
              <div class="card-header border-0">
                <h3 class="card-title"><font color='red'>{{$attivita}}</font></h3>
                <div class="card-tools">
                  <a href="#" class="btn btn-tool btn-sm">
                    <i class="fas fa-download"></i>
                  </a>
                  <a href="#" class="btn btn-tool btn-sm">
                    <i class="fas fa-bars"></i>
                  </a>
                </div>
              </div>
			  <?php
				$max=7;
				if ($att==1) $max=7;
				if ($att==2) $max=5;
				if ($att==3) $max=3;
				if ($att==4) $max=6;
				if ($att==5) $max=0;
				if ($max>0) {?>
				<div class="card-body table-responsive p-0">
                <table id='tb_attivita' class="table table-striped table-valign-middle" style="padding:0.15rem">
                  <thead>
                  <tr>
					<th></th>
                    <th style='background-color:yellow'>Ed.IND</th>
                    <th style='background-color:yellow'>Ed.COOP</th>
                    <th style='background-color:yellow'>Ed.ART.</th>

                    <th style='background-color:green;color:white'>LEGNO IND</th>
                    <th style='background-color:green;color:white'>LEGNO PMI</th>
                    <th style='background-color:green;color:white'>LEGNO ART</th>

                    <th style='background-color:orange;color:white'>MANUF. IND</th>
                    <th style='background-color:orange;color:white'>MANUF. PMI</th>
                    <th style='background-color:orange;color:white'>MANUF. ART</th>

                    <th style='background-color:blueviolet;color:white'>LAPID. IND</th>
                    <th style='background-color:blueviolet;color:white'>LAPID. PMI</th>
                    <th style='background-color:blueviolet;color:white'>LAPID. ART</th>

                    <th style='background-color:blueviolet;color:white'>CEMENTO</th>
                    <th style=''>TOTALE</th>
                    <th>More</th>
                  </tr>
                  </thead>
                  <tbody>
				  <?php
						for ($sca=1;$sca<=$max;$sca++) {
							if ($att==1) {
								if ($sca==1) $descr="CIGO";	
								if ($sca==2) $descr="CIGS";	
								if ($sca==3) $descr="CIGSD";	
								if ($sca==4) $descr="SOLIDARIETA'";	
								if ($sca==5) $descr="MONILITA'";	
								if ($sca==6) $descr="2° LIV.";	
								if ($sca==7) $descr="VERTENZA";	
							}	
							if ($att==2) {
								if ($sca==1) $descr="411";	
								if ($sca==2) $descr="INFORTUNIO";	
								if ($sca==3) $descr="CONTESTAZIONE";	
								if ($sca==4) $descr="VERTENZE FILLEA";	
								if ($sca==5) $descr="VERTENZA UVL";	
							}	

							if ($att==3) {
								if ($sca==1) $descr="F. SANIT";	
								if ($sca==2) $descr="F. PENSIONE";	
								if ($sca==3) $descr="EBRET";	

							}	

							if ($att==4) {
								if ($sca==1) $descr="ASSEMBLEE";	
								if ($sca==2) $descr="PERMAMENZE";	
								if ($sca==3) $descr="RSU ELETTE";	
								if ($sca==4) $descr="RSA NOMINATI";	
								if ($sca==5) $descr="RSL ELETTI";	
								if ($sca==6) $descr="RIUNIONI EE.BB.";	

							}	
							if ($att==5) {
								$descr="";
							}	


						?>	
						  <tr>
							<td>
							  
							  <b>{{$descr}}</b>
							</td>
							<td>
							  12
							</td>
							<td>
							  10
							</td>
							<td>
							<?php
								if (($att==1 || $att==3) && $sca==3) {?>
							  <small class="text-danger mr-1">
								<i class="fas fa-arrow-down"></i>
								-35%
							  </small>
								<?php } ?>
							  4
							</td>					

							<td>
							  10
							</td>
							<td>
							  4
							</td>					
							<td>
							<?php
								if (($att==1 || $att==3) && $sca==2) {?>
							
							  <small class="text-success mr-1">
								<i class="fas fa-arrow-up"></i>
								10%
							  </small>
							<?php } ?>
							  10
							</td>

							<td>
							  10
							</td>
							<td>
							  4
							</td>					
							<td>
							  10
							</td>

							<td>
							  10
							</td>
							<td>
							  4
							</td>					
							<td>
							  10
							</td>

							<td>
							  4
							</td>					
							<td>
							  10
							</td>


							<td>
							  <a href="#" class="text-muted">
								<i class="fas fa-search"></i>
							  </a>
							</td>
						  </tr>
						<?php	}  ?>
                  
                  </tbody>
                </table>
              </div>
			  <?php }	?>
            </div>
			
	<?php } ?>


	


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
