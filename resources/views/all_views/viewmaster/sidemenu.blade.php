<!-- l'override di stile del sidebar (con aumento della width) è in allpage !-->
@php 
	use Illuminate\Support\Facades\Storage;
	global $utenti;
	global $user_az;
	global $info_cantieri;
	global $user;
@endphp

<!-- In allpage calcolo aziende e cantieri mostrati nel side !-->
@include('all_views.viewmaster.allpage')


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
		
			
			  
				 <li class="nav-item">
					  <li class="nav-item">
						<a href="{{route('dashboard')}}" class="nav-link">
						  <i class="far fa-circle nav-icon"></i>
						  <p>Homepage-Dashboard</p>
						</a>
					  </li>
				  </li>	
		  
			@if ($user->hasRole('admin'))
				<li class="nav-item menu">
					<a href="#" class="nav-link">
					  <i class="nav-icon fas fa-users"></i>
					  <p>Gestione archivi
						<i class="right fas fa-angle-left"></i>
					  </p>
					</a>
					<ul class="nav nav-treeview">
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
						<a href="#" class="nav-link">
						  <i class="far fa-circle nav-icon"></i>
						  <p>Categorie Documenti</p>
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
					</ul>  
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
				<li class="nav-item">
					<a href="{{route('documenti_utili')}}" class="nav-link">
						<i class="far fa-circle nav-icon"></i>
						<p>Elenco documenti utili</p>
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
		  
		  @if ($user->hasRole('admin') || 1==1)
			<div class="user-panel mt-3 pb-3 mb-3 d-flex">
			<form method="POST" action="{{ route('dashboard') }}">
				<input type='hidden' name='oper_sel' id='oper_sel'>
				<input type='hidden' name='azi_sel' id='azi_sel'>
				<input type='hidden' name='per_sel' id='per_sel' value='{{$per_sel ?? ''}}'>
				@csrf
				@php ($num=0)
				@foreach($utenti as $info)	
					<a href="#" class="nav-link p-1" onclick="$('#oper_sel').val({{$info->id}});this.closest('form').submit()">
					  <i class="far fa-circle nav-icon"></i>
						<?php if (isset($oper_sel) && $oper_sel==$info->id)
							echo "<b><font color='orange'>".$info->name."</font></b>";
						else
							echo $info->name;
						?>
					</a>
					<?php 
					
						$az="";
						$vis="display:none";
						if (isset($oper_sel) && $oper_sel==$info->id) $vis="";
	
						echo "<div id='div_az$num' style='$vis'>";
						
						if (array_key_exists($info->id,$user_az)){
							
							for ($sca=0;$sca<=count($user_az[$info->id])-1;$sca++) {
								$az=$user_az[$info->id][$sca]['azienda'];
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
						
						$ref_u=strtoupper($info->email);
						if (isset($info_cantieri[$ref_u])) {
							echo "<hr>";
							echo "<font color='white'><h5>Cantieri assegnati</h5></font>";
							for ($ele=0;$ele<=count($info_cantieri[$ref_u])-1;$ele++) {
								$ragione_sociale=$info_cantieri[$ref_u][$ele]['azienda'];
								echo "<span class='d-block ml-4 text-info'>";
									echo $ragione_sociale;
								echo "</span>";
								$id_a=$info_cantieri[$ref_u][$ele]['id_azienda'];
								$id_cantiere=$info_cantieri[$ref_u][$ele]['id_cantiere'];
								
								$url="https://www.filleaoffice.it/filleago/index.php/sito/organizza?cantiere=$id_a";
								
								//$url="http://localhost/filleago/index.php/sito/organizza?cantiere=$id_a";
								
								
								echo "<a href='$url' class='ml-5 nav-link p-1' onclick=''  target='_blank'>";
									echo " - ";
									echo "<small>";
									echo $info_cantieri[$ref_u][$ele]['indirizzo_c'];
									echo "</small> ";
								echo "</a>";
								$ml="ml-5";
								
								$path = "allegati/cantieri/$id_cantiere";
								
								if(File::isDirectory($path)){
									echo "<a href='javascript:void(0)' onclick='docincantiere($id_cantiere)' class='d-inline ml-5 nav-link p-1'>";
										echo "<i class='fas fa-folder'></i>";
									echo "</a>";
									$ml="";
								}
								echo "<a href='javascript:void(0)' onclick='newdocincantiere($id_cantiere)' class='d-inline nav-link p-1 $ml'>";
									echo "<i class='fas fa-folder-plus'></i>";
								echo "</a>";

								
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
  
