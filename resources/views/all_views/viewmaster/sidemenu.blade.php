
<?php
use App\Models\User;
	$id = Auth::user()->id;
	$user = User::find($id);
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


      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
			<?php if (1==2) {?>
			 <li class="nav-item menu">
				<a href="#" class="nav-link">
				  <i class="nav-icon fas fa-cube"></i>
				  <p>Candidature
					<i class="right fas fa-angle-left"></i>
				  </p>
				</a>
				<ul class="nav nav-treeview">
				  <li class="nav-item">
					<a href="" class="nav-link">
					  <i class="far fa-circle nav-icon"></i>
					  <p>Nuova candidatura</p>
					</a>
				  </li>
				  <li class="nav-item">
					<a href="" class="nav-link">
					  <i class="far fa-circle nav-icon"></i>
					  <p>Lista candidature</p>
					</a>
				  </li>

				</ul>
			  </li>
			<?php } ?>

		
			@if ($user->hasRole('admin'))
			  <li class="nav-item menu">
				<a href="#" class="nav-link">
				  <i class="fas fa-cogs"></i> 
				  <p>
					Archivi Servizi
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
				</ul>  
			   </li>	
			   
			@endif 
			
			
          

	  
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
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
