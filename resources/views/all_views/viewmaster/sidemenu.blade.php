
<?php
use App\Models\User;
$id = Auth::user()->id;
$user = User::find($id);
$utenti=user::select('id','name')->orderBy('name')->get();
$assegnazioni=DB::table('assegnazioni as a')
->select('a.*')
->orderBy('a.id_user')
->orderBy('a.azienda')
->get();
$user_az=array();
foreach($assegnazioni as $assegnazione) {
	$id_user=$assegnazione->id_user;
	$user_az[$id_user][]=$assegnazione->azienda;
}
	
?>

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('dashboard') }}" class="brand-link">
      <img src="{{ URL::asset('/') }}dist/img/logo1.png" alt="bsFI Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">FirenzeRENDICONTA</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
			@if ($user->hasRole('admin'))
				<img src="{{ URL::asset('/') }}dist/img/AdminLTELogo.png" class="img-circle elevation-2" alt="User Image">
			@else
				<img src="{{ URL::asset('/') }}dist/img/avatar1.png" class="img-circle elevation-2" alt="User Image">
			@endif
        </div>
        <div class="info">
          <a href="#" class="d-block">{{ Auth::user()->name }}</a>
        </div>
      </div>
	  
	  <div class="user-panel mt-3 pb-3 mb-3 d-flex">

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
		
			@if ($user->hasRole('admin'))
			  
				 <li class="nav-item">
					  <li class="nav-item">
						<a href="{{route('dashboard')}}" class="nav-link">
						  <i class="far fa-circle nav-icon"></i>
						  <p>Homepage-Dashboard</p>
						</a>
					  </li>
				  </li>	
		  
				  <li class="nav-item">
					<a href="{{route('definizione_utenti')}}" class="nav-link">
					  <i class="far fa-circle nav-icon"></i>
					  <p>Definizione Utenti</p>
					</a>
				  </li>

				  <li class="nav-item">
					<a href="{{route('definizione_attivita')}}" class="nav-link">
					  <i class="far fa-circle nav-icon"></i>
					  <p>Definizione attività</p>
					</a>
				  </li>
				  
				  <li class="nav-item">
					  <li class="nav-item">
						<a href="{{route('aziende')}}" class="nav-link">
						  <i class="far fa-circle nav-icon"></i>
						  <p>Gestione Aziende</p>
						</a>
					  </li>
				  </li>
				  

			  <li class="nav-item">
				  <li class="nav-item">
					<a href="{{route('assegnazioni')}}" class="nav-link">
					  <i class="far fa-circle nav-icon"></i>
					  <p>Assegnazioni</p>
					</a>
				  </li>
			  </li>


			@endif 
          
			  <li class="nav-item">
				  <li class="nav-item">
					<a href="{{route('documenti')}}" class="nav-link">
					  <i class="far fa-circle nav-icon"></i>
					  <p>Elenco documenti</p>
					</a>
				  </li>
			  </li>


	  
		  <li class="nav-item">
				<form method="POST" action="{{ route('logout') }}">
					@csrf
					  <li class="nav-item">
						<a href="#" class="nav-link" onclick="event.preventDefault();this.closest('form').submit();">
						  <i class="far fa-circle nav-icon"></i>
						  <p>Logout</p>
						</a>
					  </li>

				</form>	
          </li>
		  
		  </ul>
		  </div>
		  
		  @if ($user->hasRole('admin'))
			<div class="user-panel mt-3 pb-3 mb-3 d-flex">
			<form method="POST" action="{{ route('dashboard') }}">
				<input type='hidden' name='oper_sel' id='oper_sel'>
				<input type='hidden' name='azi_sel' id='azi_sel'>
				<input type='hidden' name='per_sel' id='per_sel' value='{{$per_sel ?? ''}}'>
				@csrf
				@php ($num=0)
				@foreach($utenti as $info)	
					<a href="#" class="nav-link p-1" onclick="$('#oper_sel').val({{$info->id}});$('#div_az{{$num}}').show();this.closest('form').submit()">
					  <i class="far fa-circle nav-icon"></i>
					  {{$info->name}}
					</a>
					<?php 
					
						$az="";
						$vis="display:none";
						if (isset($oper_sel) && $oper_sel==$info->id) $vis="";
						echo "<div id='div_az$num' style='$vis'>";
						if (array_key_exists($info->id,$user_az)){
							for ($sca=0;$sca<=count($user_az[$info->id])-1;$sca++) {
								$az=$user_az[$info->id][$sca];
								$text="text-info";
								if (isset($azi_sel) && $azi_sel==$az) $text="text-warning";
								$js="";
								$js.="$('#oper_sel').val(".$info->id.");";
								$js.="$('#azi_sel').val('$az');";
								$js.="this.closest('form').submit();";
								
								echo "<a href='#' class='ml-4 nav-link p-1 $text' onclick=\"$js\">";
									echo $az;
								echo "</a>";
								//$info->id;
								
							}
							
						}
						
						echo "</div>";
						$num++;
					?>	
									
				@endforeach	
				</form>	
				</div>
				
		  @endif
		  
        
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
