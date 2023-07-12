<style>

 #tb_attivita td {
  padding: 0.06rem;
  vertical-align: top;
  border-top: 1px solid #dee2e6;
}
</style>
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

@endsection

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
	<form method='post' action="{{ route('dashboard') }}" id='frm_dash' name='frm_dash' autocomplete="off" class="needs-validation" autocomplete="off">
    <div class="content">
	<input name="_token" type="hidden" value="{{ csrf_token() }}" id='token_csrf'>
	<input type='hidden' name='confr' id='confr' value="{{$confr}}">
	
     
	 <div class="container-fluid">

	@if($num_noti>0)
		<div class="alert alert-warning" role="alert">
			<b>Attenzione</b><hr>
			Hai <b>{{$num_noti}}</b>
			@if ($num_noti==1) 
				nuovo documento inserito da altri riferito ad un'azienda a te associata.
			@else
				nuovi documenti inseriti da altri riferiti ad una o più aziende a te associate.
			@endif
			<br>
			<a href="{{route('documenti')}}"  class="link-primary">
				Clicca quì per accedere all'area documenti
			</a>	
		</div>
	@endif	
	 
	 <button type="button" onclick="attiva_confr()" class="btn btn-primary mb-3">Attiva/Disattiva Confronto</button>
		<div class="row mb-3">
			<div class="col-md-6">
				<div class="form-floating mb-3 mb-md-0">
					<select class="form-select" id="periodo" aria-label="Periodo" name='periodo' onchange="$('#per_sel').val(this.value);$('#frm_dash').submit();" placeholder="Periodo" >
						<option value=''>Select...</option>
						<?php
							foreach ($periodi as $id_per=>$per) {
								echo "<option value='".$id_per."' ";
								if ($periodo==$id_per) echo " selected ";
								echo ">".$per."</option>";
							}
						?>
					</select>
					<label for="periodo">Periodo</label>
				</div>
			</div>
			<input type='hidden' name='azienda' id='azienda' value='{{$azienda}}'>
			@if ($user->hasRole('admin') || 1==1)
				
				<div class="col-md-6">
					<div class="form-floating mb-3 mb-md-0">
						<select class="form-select" id="funzionario" aria-label="Funzionario" name='funzionario' onchange="$('#azienda').val('');$('#frm_dash').submit();" placeholder="Funzionario">
							<option value=''>Select...</option>
							<option value='all'
								@if ($funzionario=="all") 
									selected
								@endif
							>Tutti</option>
							<?php
								
								foreach ($users as $utente) {
									echo "<option value='".$utente->id."' ";
									if ($funzionario==$utente->id) echo " selected ";
									echo ">".$utente->name."</option>";
								}
								
							?>
						</select>
						<label for="funzionario">Funzionario</label>
					</div>
				</div>
			@endif

		</div>	
		
		<?php
			$vis="none";
			if ($confr=="1") $vis="";
		?>
		<div class="row mb-3" style='display:{{$vis}}' id='div_confr'>
			<div class="col-md-6">
				<div class="form-floating mb-3 mb-md-0">
					<select class="form-select" id="periodo1" aria-label="Periodo1" name='periodo1' onchange="$('#frm_dash').submit();" placeholder="Periodo confronto" >
						<option value=''>Select...</option>
						<?php
							foreach ($periodi as $id_per=>$per) {
								//if (substr($per,0,7)=="Globale") continue;
								echo "<option value='".$id_per."' ";
								if ($periodo1==$id_per) echo " selected ";
								echo ">".$per."</option>";
							}
						?>
					</select>
					<label for="periodo1">Periodo Confronto</label>
				</div>
			</div>
			@if ($user->hasRole('admin') || 1==1)
				<div class="col-md-6">
					<div class="form-floating mb-3 mb-md-0">
						<select class="form-select" id="funzionario1" aria-label="Funzionario1" name='funzionario1' onchange="$('#frm_dash').submit();" placeholder="Funzionario1">
							<option value=''>Select...</option>
							<option value='all'
								@if ($funzionario1=="all") 
									selected
								@endif
							>Tutti</option>
							<?php
								
								foreach ($users as $utente) {
									echo "<option value='".$utente->id."' ";
									if ($funzionario1==$utente->id) echo " selected ";
									echo ">".$utente->name."</option>";
								}
								
							?>
						</select>
						<label for="funzionario1">Funzionario Confronto</label>
					</div>
				</div>
			@endif

		</div>			
		<?php
		

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
							
							$id_attivita=$attivita[$sca]['id_attivita'];
							
							$js=" onclick=\"setvalue($ref_user,'$periodo',$categoria,$id_attivita);\"";
							if (substr($periodo,0,7)=="Globale" || $funzionario=="all") $js="";
							
							$descr=$attivita[$sca]['descrizione'];
							echo "<tr>";
								echo "<td><b>";
									echo "<a href='javascript:void(0)' class='text-muted' $js>$descr</a>";
								echo "</b></td>";
								
								foreach($settori as $id_settore=>$v) {
									$js_view=" onclick=\"view_row('$ref_user','$periodo',$categoria,$id_attivita,$id_settore,'$azienda');\"";
									
									echo "<td style='text-align:center'>";
									  echo "<a href='javascript:void(0)' class='text-muted' $js_view>";
										
										$v1="?";
										
										if (isset($schema[$categoria][$id_attivita][$id_settore])) 
											$v1=$schema[$categoria][$id_attivita][$id_settore];

										$v2="?";
										if (isset($schema1[$categoria][$id_attivita][$id_settore])) {
											$v2=$schema1[$categoria][$id_attivita][$id_settore];
										}	
										echo view_value($v1,$v2);
									  echo "</a>";
									echo "</td>";
								}	
							echo "<td></td>";
							echo "<td>";
								echo "<a href='javascript:void(0)' class='text-muted' $js>";
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

<?php
	function view_value($v1,$v2) {
		$view=null;$color="";
		if ($v1!="?" && $v2!="?") {
			$v1=intval($v1);$v2=intval($v2);
			
			if ($v1>$v2) {$tipo="success";$arr="arrow-up";$color="green";}
			if ($v1<$v2) {$tipo="danger";$color="red";$arr="arrow-down";}
			if ($v1!=$v2) {				
				$view.="<small class='text-$tipo mr-1'>";
				$view.="<i class='fas fa-$arr'></i>";
				
				if ($v2>0) {
					$perc=abs(100-(100/$v2)*$v1);
					$perc=number_format($perc,0);
					$perc.="%";
					$view.=" <font color='$color'>$perc</font> ";
				}
				$view.="</small> ";
			} 
		}

		if ($v1!="?") $view.="<font color='$color'>$v1</font>";
		if ($v2!="?") $view.=":$v2";
		
		return $view;
	}
	
?>


               

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
	  <div id='div_wait' class='mb-3'></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal" id='btn_close' onclick='close_doc()'>Chiudi</button>
        <div id='div_save'></div>
      </div>
	  
    </div>
  </div>
</div>	
	
	</form>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
 @endsection
 
@section('content_plugin')
	<!-- jQuery -->
	<script src="{{ URL::asset('/') }}plugins/jquery/jquery.min.js"></script>
	<!-- Bootstrap 4 -->
	<script src="{{ URL::asset('/') }}plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
	<!-- AdminLTE App -->
	<script src="{{ URL::asset('/') }}dist/js/adminlte.min.js"></script>
	
	<script src="{{ URL::asset('/') }}dist/js/dash.js?ver=1.368"></script>

	<!--select2 !-->
	<script src="{{ URL::asset('/') }}plugins/select2/js/select2.full.min.js"></script>	
	<!-- per upload -->
	<script src="{{ URL::asset('/') }}dist/js/upload/jquery.dm-uploader.min.js"></script>
	<script src="{{ URL::asset('/') }}dist/js/upload/demo-ui.js?ver=1.300"></script>
	<script src="{{ URL::asset('/') }}dist/js/upload/demo-config.js?ver=2.393"></script>	
	
@endsection
